<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Favorite;
use App\Models\Commission;
use App\Models\ServiceEventDays;
use Illuminate\Support\Facades\DB;

class PropertiesServices
{
    public function getProperties(array $filters = [])
    {
        $data = $this->buildFilteredQuery($filters);
        $requiredFeatures = $filters['features'] ? array_map('intval', $filters['features']) : [];
        $services = [];
        foreach ($data as $service) {


            $check_futures_days = $this->check_futures_dates($filters, $service->days);
            if (! $check_futures_days)
                continue;
            $serviceFeatureIds = array_map('intval', $service->features->pluck('feature_id')->toArray()); // Convert to integers
            if (! empty(array_diff($requiredFeatures, $serviceFeatureIds)))
                continue;

            if ($service->user->blocked == 0) {
                $distance = Helpers::distance_between_two_locations([
                    "user_lat" => $filters['lat'],
                    "user_lng" => $filters['long'],
                    "service_lat" => $service->lat,
                    "service_lng" => $service->lng,
                ]);


                $check = 0;
                if ($filters['user']) {
                    $check = Favorite::where("service_id", $service->id)
                        ->where("user_id", $filters['user']->id)->exists() ? 1 : 0;


                    $totalRating = $service->review->sum('rating');
                    $reviewCount = $service->review->count();
                    $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;
                    $booking_count = 0;
                    if ($filters['user'])
                        $booking_count = $filters['user']->power == 'provider' ?  $service->booking_count : 0;
                    $services[] = $this->formatService($service, $check, $distance, $averageRating, $booking_count);
                }
            }
        }
        return $services;
    }
    private function buildFilteredQuery($filters)
    {

        $query = Service::query();

        if (!empty($filters['text'])) {
            $text = $filters['text'];
            $query->where(function ($q) use ($text) {
                $q->where("name", "like", "%$text%")
                    ->orWhere("name_ar", "like", "%$text%");
            });
        }

        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $query->whereBetween('price', [$filters['min_price'], $filters['max_price']]);
        }

        if (!empty($filters['min_area']) && !empty($filters['max_area'])) {
            $query->whereBetween('property_size', [$filters['min_area'], $filters['max_area']]);
        }

        if (!empty($filters['bed']) && $filters['bed'] > 0) {
            $query->where('bed', $filters['bed']);
        }

        if (!empty($filters['bath']) && $filters['bath'] > 0) {
            $query->where('bath', $filters['bath']);
        }

        if (!empty($filters['living_room']) && $filters['living_room'] > 0) {
            $query->where('living_room', $filters['living_room']);
        }

        $userLatitude = $filters['lat'] ?? null;
        $userLongitude = $filters['long'] ?? null;

        if ($userLatitude && $userLongitude) {
            $haversine = "(6371 * acos(cos(radians($userLatitude)) * cos(radians(lat)) * cos(radians(lng) - radians($userLongitude)) + sin(radians($userLatitude)) * sin(radians(lat))))";
            $query->select('services.*', DB::raw("$haversine AS distance"));
        }

        if (!empty($filters['highest_price'])) {
            $query->orderBy("price", "DESC");
        } elseif (!empty($filters['lowest_price'])) {
            $query->orderBy("price", "ASC");
        } elseif ($userLatitude && $userLongitude) {
            $query->orderBy('distance', 'ASC');
        } else {
            $query->orderBy('id', 'DESC');
        }

        // User role filtering
        if (!empty($filters['user'])) {
            $user = $filters['user'];
            if ($user->power == "customer") {
                $query->where("accept", 1);
            } elseif ($user->power == "provider") {
                $query->where("user_id", $user->id)->withCount('booking');
            }
        }

        if (isset($filters['accept'])) {
            $query->where("accept", $filters['accept']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        if (!empty($filters['property'])) {
            $query->where('property', $filters['property']);
        }

        if (!empty($filters['duration'])) {
            $query->where('duration', $filters['duration']);
        }


        // Optional: Define $subscribers if required (not shown in your example)
        $subscribers = $filters['subscribers'] ?? [];

        $data = $query
            ->whereIn("user_id", $subscribers)
            ->where("disabled", 0)
            ->with(['booking', 'features', 'gallery', 'review', 'user'])
            ->limit(50)
            ->withCount('booking')
            ->get();

        // === Filter Range of days is avaliable
        if (!empty($filters['range_days']) && is_array($filters['range_days'])) {
            $parsedRanges = [];

            // Convert range strings to Carbon date objects
            foreach ($filters['range_days'] as $range) {
                if (count($range) == 2) {
                    try {
                        $start = \Carbon\Carbon::parse($range[0])->startOfDay();
                        $end = \Carbon\Carbon::parse($range[1])->endOfDay();
                        $parsedRanges[] = [$start, $end];
                    } catch (\Exception $e) {
                    }
                }
            }            
            $data = $data->filter(function ($service) use ($parsedRanges) {
                $days = collect(json_decode($service->days ?? '[]'))
                    ->map(function ($dateString) {
                        try {
                            return \Carbon\Carbon::createFromFormat('m/d/Y g:i A', $dateString)->toDateString();
                        } catch (\Exception $e) {
                            return null;
                        }
                    })
                    ->filter() // Remove nulls
                    ->unique(); // إزالة التكرار لو موجود

                // نجمع كل الأيام المطلوبة من كل الـ ranges
                $requiredDays = collect($parsedRanges)->flatMap(function (array $range) {
                    [$start, $end] = $range;
                    return collect();
                })->merge(
                    collect($parsedRanges)->flatMap(function (array $range) {
                        [$start, $end] = $range;
                        $dates = [];
                        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                            $dates[] = $date->toDateString();
                        }
                        return $dates;
                    })
                )->unique();

                // تحقق إن كل الأيام المطلوبة موجودة في days
                return $requiredDays->every(fn($day) => $days->contains($day));
            })->values();

            // $data = $data->filter(function ($service) use ($parsedRanges) {
            //     $days = json_decode($service->days ?? '[]');

            //     foreach ($days as $dateString) {
            //         try {
            //             $day = \Carbon\Carbon::createFromFormat('m/d/Y g:i A', $dateString);
            //             foreach ($parsedRanges as [$start, $end]) {
            //                 if ($day->between($start, $end)) {
            //                     return true;
            //                 }
            //             }
            //         } catch (\Exception $e) {
            //         }
            //     }

            //     return false;
            // })->values(); // Reset keys

        }

        return $data;
    }
    private function formatService($service, $check, $distance, $averageRating, $booking_count)
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'name_ar' => $service->name_ar,
            "category_id" => $service->category_id,
            "user_id" => $service->user_id,
            "floor" => $service->floor,
            "bed" => $service->bed,
            "bath" => $service->bath,
            "price" => $service->price + $service->commission_money,
            "description" => $service->description,
            "description_ar" => $service->description_ar,
            "days" => $service->days,
            'range_days' => $service->range_days,
            "lat" => $service->lat,
            "lng" => $service->lng,
            "available" => $service->available,
            "accept" => $service->accept,
            "image" => $service->image,
            'is_favorited' => $check,
            "created_at" => $service->created_at,
            'place' => $service->place,
            'place_ar' => $service->place_ar,
            // "eventDays" => $eventDays,
            // "features" => $features,
            // "gallery" => $service->gallery,
            "regular_price" => $service->regular_price,
            "rate" => $averageRating,
            "user" => $service->user,
            "distance" => $distance,
            'booking_count' => $booking_count,

        ];
    }
    private function check_futures_dates($filters, $service_days)
    {
        $filtersApplied = !empty($filters['text']) ||
            !empty($filters['min_price']) ||
            !empty($filters['max_price']) ||
            !empty($filters['min_area']) ||
            !empty($filters['max_area']) ||
            !empty($filters['bed']) ||
            !empty($filters['bath']) ||
            !empty($filters['living_room']) ||
            !empty($filters['features']) ||
            !empty($filters['category_id']) ||
            !empty($filters['city_id']) ||
            !empty($filters['property_type']) ||
            !empty($filters['property']) ||
            !empty($filters['duration']) ||
            !empty($filters['range_days']);
        if ($filtersApplied) {
            $hasFutureDate = false;
            $days = json_decode($service_days ?? '[]');

            foreach ($days as $dayStr) {
                try {
                    $day = \Carbon\Carbon::createFromFormat('m/d/Y g:i A', $dayStr);
                    if ($day->isFuture()) {
                        $hasFutureDate = true;
                        break;
                    }
                } catch (\Exception $e) {
                    // ignore invalid dates
                }
            }
            return $hasFutureDate;
        }
        return true;
    }

    public function apply_event_days()
    {
        // Cache today's date
        $today = date("m/d/Y");

        // Batch process event days for today
        $eventDays_of_today_first_time = ServiceEventDays::where('day', $today)->where("status", 0)->get();
        $eventDays_of_old_todays_sec_time = ServiceEventDays::where('day', '<', $today)->where("status", 1)->get();

        // Use collections for bulk updates
        if ($eventDays_of_today_first_time->isNotEmpty()) {
            $todayEvents = $eventDays_of_today_first_time->map(function ($event) {
                $service = $event->service;
                $commission_money = (($service->commission_percentage / $event->price) * 100);
                $service->update([
                    "price" => $event->price + $commission_money,
                    'commission_money' => $commission_money,
                    "provider_money" => $event->price - $commission_money,
                ]);
                $event->update(['status' => 1]);
            });
        }

        if ($eventDays_of_old_todays_sec_time->isNotEmpty()) {
            $oldEvents = $eventDays_of_old_todays_sec_time->map(function ($event) {
                $service = $event->service;
                $commission_money = (($service->commission_percentage / $service->regular_price) * 100);
                $service->update([
                    "price" => $service->regular_price + $commission_money,
                    'commission_money' => $commission_money,
                    "provider_money" => $service->regular_price - $commission_money,
                ]);
                $event->update(['status' => 2]);
            });
        }
    }
    private function apply_commision($provider_id, $price)
    {
        $commission = Commission::where("provider_id", $provider_id)->first() ?? Setting::find(1);
        if ($commission) {
            $commission_id = $commission->id;
            $commission_value = $commission->commission_value;

            if ($commission->commission_type == "percentage")
                $commission_money = ($price * $commission_value) / 100;
            else
                $commission_money =  $commission_value;
            $provider_money = $price;
        } else {
            $commission_value = $commission->commission_value;
            $commission_id = 0;

            if ($commission->commission_type == "percentage")
                $commission_money = ($price * $commission_value) / 100;
            else
                $commission_money =  $commission_value;
            $provider_money = $price;
        }
        return [
            'commission_id' => $commission_id,
            'commission_value' => $commission_value,
            'commission_money' => $commission_money,
            'provider_money' => $provider_money,
        ];
    }
    public function createService($request, $image, $document, $days, $provider_id = null)

    {
        $commissionData = $this->apply_commision($provider_id, $request->price);
        $service = Service::create([
            'commission_id' => $commissionData['commission_id'],
            "commission_percentage" => $commissionData['commission_value'],
            "commission_money" => $commissionData['commission_money'],
            "provider_money" => $commissionData['provider_money'],
            // added commision to price 
            "price" => $request->price,
            "regular_price" => $request->price,
            "name" => $request->name_en,
            "name_ar" => $request->name_ar ?? $request->name_en,
            "place" => $request->place_en,
            "place_ar" => $request->place_ar ?? $request->place_en,
            "category_id" => $request->category_id,
            "user_id" => $provider_id,
            "living_room" => $request->living_room,
            "bed" => $request->bed,
            "bath" => $request->bath,
            "description" => $request->description_en,
            "description_ar" => $request->description_ar ?? $request->description_en,
            "days" => json_encode($days),
            "range_days" => (string) ($request->range_days),
            "lat" => $request->latitude,
            "lng" => $request->longitude,
            "accept" => $request->has('accept') ? 1 :  2,
            "image" => "files/$image",
            "document" => "files/$document",
            "city_id" => $request->city_id,
            "property" => $request->property,
            "duration" => $request->duration,
            "property_type" => $request->property_type,
            "property_size" => $request->property_size,
        ]);

        return $service;
    }
}
