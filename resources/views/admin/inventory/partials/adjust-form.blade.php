{{-- resources/views/admin/inventory/partials/adjust-form.blade.php --}}
<form method="POST" action="{{ $action }}" class="adjust-form">
    @csrf
    @method('PATCH')
    <input type="number" name="change" step="{{ $step }}" placeholder="+5 or -2" required>
    <select name="reason" required>
        <option value="delivery">Delivery</option>
        <option value="wastage">Wastage</option>
        <option value="count_correction">Count correction</option>
    </select>
    <button type="submit">Save</button>
</form>
