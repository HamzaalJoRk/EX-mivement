<div class="mb-3">
    <label>Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $violation->title ?? '') }}">
</div>

<div class="mb-3">
    <label>Fee</label>
    <input type="number" step="0.01" name="fee" class="form-control" value="{{ old('fee', $violation->fee ?? '') }}">
</div>