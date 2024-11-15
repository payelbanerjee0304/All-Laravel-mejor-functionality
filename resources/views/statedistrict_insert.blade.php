<x-layout>
    <x-slot:pageheading>
        {{ 'Import States Data' }}
    </x-slot:pageheading>

    <!-- Display success or error messages -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex">
        <!-- State Import Card -->
        <div class="card mr-3" style="width: 18rem;">
            <img src="{{ asset('profilePicture/1725338488_profile_picture.jpg') }}" class="card-img-top" alt="noimage">
            <div class="card-body">
                <h5 class="card-title">State Details</h5>
                <form action="{{ route('data.import.states') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Click here to Import States</button>
                </form>
            </div>
        </div>

        <!-- District Import Card -->
        <div class="card" style="width: 18rem;">
            <img src="{{ asset('profilePicture/1725338488_profile_picture.jpg') }}" class="card-img-top" alt="noimage">
            <div class="card-body">
                <h5 class="card-title">District Details</h5>
                <form action="{{ route('data.import.districts') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Click here to Import Districts</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>