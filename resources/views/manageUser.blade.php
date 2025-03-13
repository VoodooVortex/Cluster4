@extends('layouts.default')

@section('content')

<div class="container mx-auto p-6">
    <div class="px-6 py-4 flex justify-between items-center bg-4D55A0-100 border-[#4D55A0]">
        <h2 class="text-2xl font-bold mb-4">จัดการบัญชีผู้ใช้</h2>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center bg-white-100 border-b">
            <h5 class="text-lg font-semibold">บัญชีทั้งหมด</h5>
            {{-- <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg">เพิ่มบัญชี</a> --}}
        </div>
        <div class="p-6">
            <input type="text" class="w-full p-2 border border-gray-300 rounded-lg mb-3" placeholder="ค้นหาบัญชี" id="searchUser">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 border"> <input type="checkbox" id="selectAll"> </th>
                        <th class="p-3 border">ชื่อ</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach($users as $user)
                    <tr class="border">
                        <td class="p-3 border text-center"> <input type="checkbox" class="userCheckbox"> </td>
                        <td class="p-3 border">{{ $user->name }}</td>
                        <td class="p-3 border">{{ $user->email }}</td>
                        <td class="p-3 border"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg">{{ $user->role }}</span></td>
                        <td class="p-3 border flex space-x-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-lg">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>



{{-- <table class="table-auto">
    <thead>
      <tr>
        <th>Song</th>
        <th>Artist</th>
        <th>Year</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>The Sliding Mr. Bones (Next Stop, Pottersville)</td>
        <td>Malcolm Lockyer</td>
        <td>1961</td>
      </tr>
      <tr>
        <td>Witchy Woman</td>
        <td>The Eagles</td>
        <td>1972</td>
      </tr>
      <tr>
        <td>Shining Star</td>
        <td>Earth, Wind, and Fire</td>
        <td>1975</td>
      </tr>
    </tbody>
  </table> --}}

@endsection
