@csrf
<div class="mb-3">
    <label for="name" class="form-label">Institute Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $institute->name ?? '') }}" required>
</div>
<button type="submit" class="btn btn-success">Save</button>
<a href="{{ route('institutes.index') }}" class="btn btn-secondary">Back</a>
