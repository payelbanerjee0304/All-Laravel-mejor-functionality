<x-layout>
    <x-slot:color>
        {{'info'}}
    </x-slot:color>
    <x-slot:pageheading>
        {{'QR Code/BarCode Page'}}
    </x-slot:pageheading>

    @if(isset($user))
    <div class="table-responsiv" id="tableData">
		<table border="1" cellpadding="20" cellspacing="0">
            <tr>
                <td>QR Code</td>
                <td>BarCode</td>
            </tr>
            <tr>
                <td>{!! QrCode::size(100)->generate($user->name . ', ' . $user->email . ', ' . $user->phone) !!}</td>
                <td>{!! DNS1D::getBarcodeHTML($user->name . ', ' . $user->email . ', ' . $user->phone, 'C128', 0.5, 30) !!}</td>
            </tr>
        </table>
    @endif

</x-layout>