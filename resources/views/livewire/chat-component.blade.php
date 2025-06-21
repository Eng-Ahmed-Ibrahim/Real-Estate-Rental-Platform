<div>
    <form wire:submit.prevent="store">
        <input type="text" wire:model="feature_name_ar" placeholder="Feature Name AR">
        <input type="text" wire:model="feature_name" placeholder="Feature Name">
        <button type="submit">Add Feature</button>
    </form>

    @if($message)
        <div>{{ $message }}</div>
    @endif

    <ul>
        @foreach($features as $feature)
            <li>{{ $feature->feature_name }} ({{ $feature->feature_name_ar }})</li>
        @endforeach
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        Echo.channel('features-channel')
            .listen('FeaturesEvent', (e) => {
                console.log(e.feature);
                // You can refresh the features list or do other actions here
                Livewire.emit('featureAdded', e.feature);
            });
    });
</script>
