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
                <input type="text" id="keyword" name="keyword" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" id="searchSubmit" class="btn btn-default" style="background-color: rgb(76, 137, 172)">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            <a href="{{route('admin.logout')}}" class="btn btn-success" >Logout</a>
            <a href="{{ route('download') }}" class="btn btn-primary">Download CSV</a>
        </div>
    </div>
    <div class="table-responsiv" id="tableData">
		<table border="1" cellpadding="20" cellspacing="0" class="table table-hover table-border">
			<tr>
				<th>SL.No.</th>
				<th>Name</th>
				<th>Email</th>
				{{-- <th>Password</th> --}}
				<th>Phone</th>
				<th>Gender</th>
                <th>State</th>
                <th>District</th>
                <th>City</th>
				<th>Language</th>
				<th>Profile Picture</th>
                <th>Images</th>
				<th>Action</th>
                <th>Details</th>
                {{-- <th>QR Code</th>
                <th>Bar Code</th>
                <th>Send</th> --}}
			</tr>
			
			@php 
			$i=1;
			@endphp
			@foreach($user->all() as $all)
			<tr>
				<td>@php echo $i; $i++; @endphp</td>
				<td>{{$all->name}}</td>
				<td>{{$all->email}}</td>
				{{-- <td>{{$all->password}}</td> --}}
				<td>{{$all->phone}}</td>
				<td>{{$all->gender}}</td>
				<td>
                    @php
                        $state = $stateDB->firstWhere('_id', $all->state);
                    @endphp
                    {{$state->name }}
                </td>
				<td>
                    @php
                        $district = $districtDB->firstWhere('_id', $all->district);
                    @endphp
                    {{$district ? $district->name : 'N/A' }}
                </td>
				<td>
                    @php
                        $city = $cityDB->firstWhere('_id', $all->city);
                    @endphp
                    {{$city? $city->name : 'N/A' }}
                </td>
                <td>{{ implode(', ', $all->language) }}</td>
                <td><img src="{{$all->profile_picture}}" height="50" alt="img"></td>
                <td>
                    @foreach($all->images as $image)
                        <img src="{{ $image }}" alt="imgs" height="25">
                    @endforeach
                </td>
                <td><a href="{{url('/edit')}}{{$all->id}}"> <i class="fa fa-edit" style="font-size:18px;color:red"></i></a> 
					{{-- <a href="{{url('/unblock')}}{{$all->id}}">Unblock</a> ||  --}}
					<a href="#" onclick="confirmDelete('{{ $all->id }}')"><i class="fa fa-trash" style="font-size:18px;color:red"></i></a>
                </td>
                {{-- <td>
                    {!! QrCode::size(50)->generate($all->name . ', ' . $all->email . ', ' . $all->phone) !!}
                </td>
                <td>
                    {!! DNS1D::getBarcodeHTML($all->name . ', ' . $all->email . ', ' . $all->phone, 'C128', 0.5, 30) !!}
                </td> --}}
                <td>
                    <a href="{{url('/codes')}}{{$all->id}}"><i class="fa fa-address-card" style="font-size:24px;color:red"></i></a>
                </td>
			</tr>
			@endforeach
		</table>
	
        <div class="">
            {{ $user->links() }}
        </div>
    </div>


    <script>
        //pagination
        $(document).ready(function(){
            $(document).on('click','.pagination a',function(e){
                e.preventDefault();
                let page=$(this).attr('href').split('page=')[1];
                registrationPagination(page);
            })
        });
        function registrationPagination(page){
            let keyword = $('#keyword').val();
            // console.log(keyword);
            $.ajax({
                url: "/pagination/paginate-data",
                data: {
                        page: page,
                        keyword:keyword,
                    },
                success: function(response){
                    $('#tableData').html(response);
                }
            });
        }
        //search products
        $(document).ready(function(){
            $(document).on('click','#searchSubmit',function(e){

                e.preventDefault();
                let keyword = $('#keyword').val();
                // console.log(keyword);
                $.ajax({
                    url:'{{route("search")}}',
                    method:'GET',
                    data:{
                        keyword:keyword
                    },
                    success: function(response){
                        $('#tableData').html(response);
                    }
                });
            });
        }); 
        

        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = '/delete/' + userId;
            }
        }
    </script>

</x-layout>