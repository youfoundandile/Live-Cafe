{{-- resources/views/admin/products/partials/recipe-row.blade.php --}}
<div class="recipe-row">
    <select name="ingredients[{{ $index }}][id]" required>
        <option value="">Choose an ingredient</option>
        @foreach ($ingredients as $ingredient)
            <option value="{{ $ingredient->id }}" @selected(($row['id'] ?? null) == $ingredient->id)>
                {{ $ingredient->name }} ({{ $ingredient->unit }})
            </option>
        @endforeach
    </select>

    <input type="number" name="ingredients[{{ $index }}][quantity]" value="{{ $row['quantity'] ?? '' }}"
           step="0.001" min="0.001" placeholder="Amount for one" required>

    <button type="button" class="recipe-remove">Remove</button>
</div>
