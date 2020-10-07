<table class="table">
    <thead>
    <tr>
        <th width="50px" class="text-center">No</th>
        <th width="200px">Nama</th>
        <th>No.Telp</th>
        <th>Email</th>
        <th>Instansi</th>
        <th>Pesan</th>
        @if(in_array('delete', $action))
            <th width="50px"></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if($pesan instanceof \Illuminate\Pagination\Paginator)
        @php
            $no = isset($pesan) ? $pesan->firstItem() : 1;
        @endphp
    @else
        @php($no = 1)
    @endif
    @forelse($pesan as $key => $value)
        <tr>
            <td class="text-center bg-white">{{ $no++ }}</td>
            <td class="bg-white">{{ $value->nama }}</td>
            <td class="bg-white">{{ $value->notelp }}</td>
            <td class="bg-white">{{ $value->email }}</td>
            <td class="bg-white">{{ $value->instansi }}</td>
            <td class="bg-white" style="white-space: normal;word-wrap: break-word;">{!! $value->pesan !!}</td>
            @if(in_array('delete', $action))
                <td class="p-2 text-right bg-white">
                    <button type="button" class="btn btn-sm btn-danger px-3" title="Hapus" onclick="hapus_data({{ $value->id }})">
                        Hapus
                    </button>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="99" class="text-center bg-white">Data kosong</td>
        </tr>
    @endforelse
    </tbody>
</table>
@if(method_exists($pesan,'links'))
    {{ $pesan->links() }}
@endif
