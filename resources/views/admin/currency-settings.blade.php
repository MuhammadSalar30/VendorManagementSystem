@extends('layouts.admin', ['title' => 'Currency Settings', 'subtitle' => 'Settings'])

@section('content')
<div class="p-6 space-y-6">
    {{-- Success / Error Alerts --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded border border-green-300">
             {{ session('success') }}
        </div>
    @endif

    {{-- Add Currency Form --}}
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold mb-3">Add New Currency</h2>
        <form method="POST" action="{{ route('admin.currency-settings.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @csrf
            <input type="text" name="name" placeholder="Currency name" required class="border rounded px-3 py-2 focus:ring w-full">
            <input type="text" name="code" placeholder="Code (e.g. USD)" required class="border rounded px-3 py-2 focus:ring w-full">
            <input type="text" name="symbol" placeholder="Symbol (e.g. $ or PKR)" required class="border rounded px-3 py-2 focus:ring w-full">
            <input type="number" step="0.0001" name="rate" placeholder="Rate" value="1" class="border rounded px-3 py-2 focus:ring w-full">
            <button type="submit" class="py-2.5 px-4 inline-flex rounded-lg text-sm font-medium bg-primary text-white transition-all hover:bg-primary-500">
               Add Currency
            </button>
        </form>
    </div>

    {{-- Currency List --}}
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold mb-3">Available Currencies</h2>
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Code</th>
                    <th class="px-4 py-2 border">Symbol</th>
                    <th class="px-4 py-2 border">Rate</th>
                    <th class="px-4 py-2 border">Default</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($currencies as $currency)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $currency->id }}</td>
                        <td class="px-4 py-2 border">{{ $currency->name }}</td>
                        <td class="px-4 py-2 border font-semibold">{{ $currency->code }}</td>
                        <td class="px-4 py-2 border">{{ $currency->symbol }}</td>
                        <td class="px-4 py-2 border">{{ $currency->rate }}</td>
                        <td class="px-4 py-2 border">
                            @if($currency->is_default)
                                <span class="text-green-600 font-bold">✔ Default</span>
                            @else
                                <form action="{{ route('admin.currency-settings.default', $currency->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        Set Default
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="px-4 py-2 border flex gap-2">
                            {{-- Edit Button --}}
                            <button type="button" onclick="openEditModal({{ $currency->id }}, '{{ $currency->name }}', '{{ $currency->code }}', '{{ $currency->symbol }}', {{ $currency->rate }})"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                ✏ Edit
                            </button>

                            {{-- Delete --}}
                            <form action="{{ route('admin.currency-settings.destroy', $currency->id) }}" method="POST" onsubmit="return confirm('Delete this currency?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 border text-center text-gray-500">No currencies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Edit Currency</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-3">
                <input type="text" name="name" id="editName" placeholder="Currency name" required
                       class="border rounded px-3 py-2 w-full focus:ring">
                <input type="text" name="code" id="editCode" placeholder="Code" required
                       class="border rounded px-3 py-2 w-full focus:ring">
                <input type="text" name="symbol" id="editSymbol" placeholder="Symbol" required
                       class="border rounded px-3 py-2 w-full focus:ring">
                <input type="number" step="0.0001" name="rate" id="editRate" placeholder="Rate" required
                       class="border rounded px-3 py-2 w-full focus:ring">
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Currency</button>
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, code, symbol, rate) {
    // Set form action with the correct route
    document.getElementById('editForm').action = '{{ url("admin/currency-settings") }}/' + id;

    // Fill form values
    document.getElementById('editName').value = name;
    document.getElementById('editCode').value = code;
    document.getElementById('editSymbol').value = symbol;
    document.getElementById('editRate').value = rate;

    // Show modal
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target.id === 'editModal') {
        closeEditModal();
    }
});
</script>
@endsection
