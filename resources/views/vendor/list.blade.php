@extends('layouts.admin', ['subtitle' => "Vendor Setup", 'title' => "Vendors List"])
@section('title', 'Vendor List')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        {{-- <h1 class="text-2xl font-bold text-gray-800">Vendor List</h1> --}}
        <a href="{{ route('admin.vendors.create') }}"
        {{-- hover:bg-blue-700 rounded-lg shadow-md transition --}}

           class="flex items-center justify-center gap-2 rounded-full border border-primary bg-primary px-6 py-2.5 text-center text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:border-primary-700 hover:bg-primary-500 rounded-lg shadow-md transition">
            + Create Vendor
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Phone</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">City</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if(isset($vendors) && $vendors->count() > 0)
                    @foreach($vendors as $vendor)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $vendor->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $vendor->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $vendor->phone }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $vendor->city }}</td>
                            <td class="px-4 py-3">
                                @if($vendor->status === 'active')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.vendors.edit', $vendor->id) }}"
                                       class="px-3 py-1 text-xs rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.vendors.destroy', $vendor->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this vendor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 text-xs rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No vendors found
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
