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
        <th>Language</th>
        <th>Profile Picture</th>
        <th>Images</th>
        <th>Action</th>
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
        <td><a href="{{url('/edit')}}{{$all->id}}">Edit</a> || 
            {{-- <a href="{{url('/unblock')}}{{$all->id}}">Unblock</a> ||  --}}
            <a href="{{url('/delete')}}{{$all->id}}">Delete</a>
        </td>
    </tr>
    @endforeach
</table>

<div class="">
    {{ $user->links() }}
</div>