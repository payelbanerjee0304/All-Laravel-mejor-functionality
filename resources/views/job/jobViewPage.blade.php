<x-layout>
    <x-slot:color>
        {{'info'}}
    </x-slot:color>
    <x-slot:pageheading>
        {{'Listing'}}
    </x-slot:pageheading>
    <div class="mb-3">
        <div class="card-tools">
            <div class="input-group input-group" style="width: 250px;">
                {{-- <input type="text" id="keyword" name="keyword" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" id="searchSubmit" class="btn btn-default" style="background-color: rgb(76, 137, 172)">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div> --}}
            {{-- <a href="{{ route('downloadAssignedUser') }}" class="btn btn-primary">Download</a> --}}
            <button  class="btn btn-primary" id="downloadButton">Download Assigned Users</button>

        </div>
    </div>
    <div class="table-responsiv" id="tableData">
		<table border="1" cellpadding="20" cellspacing="0" class="table table-hover table-border">
			<tr>
				<th>SL.No.</th>
				<th>Name</th>
				<th>Email</th>
				<th>Password</th>
				<th>Phone</th>
				<th>Gender</th>
                <th>State</th>
                <th>District</th>
                <th>City</th>
			</tr>
			
			@php 
			$i=1;
			@endphp
			@foreach($user->all() as $all)
			<tr>
				<td>@php echo $i; $i++; @endphp</td>
				<td>{{$all->name}}</td>
				<td>{{$all->email}}</td>
				<td>{{$all->password}}</td>
				<td>{{$all->phone}}</td>
				<td>{{$all->gender}}</td>
				<td>{{$all->state}}</td>
				<td>{{$all->district}}</td>
				<td>{{$all->city}}</td>
			</tr>
			@endforeach
		</table>
	
        <div class="">
            {{ $user->links() }}
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle the click event on the download button
            document.getElementById('downloadButton').addEventListener('click', function() {
                // Make an AJAX request to start the export job
                fetch('/download-assigned-user')
                    .then(response => response.json())
                    .then(data => {
                        if (data.download_link) {
                            // Automatically trigger the file download
                            window.location.href = data.download_link;
                        } 
                    });
            });
        });
    </script>
    
    
    

</x-layout>