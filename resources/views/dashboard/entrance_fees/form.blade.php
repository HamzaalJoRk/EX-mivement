<div class="mb-3">
    <label for="duration" class="form-label">Duration</label>
    <input type="text" name="duration" class="form-control" value="{{ old('duration', $entranceFee->duration ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="fees" class="form-label">Fees</label>
    <input type="number" name="fees" step="0.01" class="form-control" value="{{ old('fees', $entranceFee->fees ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="type" class="form-label">type</label>
    <select name="type" class="form-control">
        <option value="">حدد نوع السيارة</option>
        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}> سيارة سياحية</option>
        <option value="2" {{ old('type') == 2 ? 'selected' : '' }}> باص</option>
    </select>
</div>
