<div class="mb-1">
    <label>العنوان</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $violation->title ?? '') }}">
</div>

<div class="mb-1">
    <label>المبلغ</label>
    <input type="number" step="0.01" name="fee" class="form-control" value="{{ old('fee', $violation->fee ?? '') }}">
</div>