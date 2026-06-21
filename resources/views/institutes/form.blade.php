@csrf

<div class="mb-3">
    <label for="name" class="form-label">
        Institute Name
    </label>

    <input
        type="text"
        name="name"
        class="form-control"
        value="{{ old('name', $institute->name ?? '') }}"
        required
    >
</div>

<div class="d-flex gap-2 mt-4">

    <button type="submit" class="btn btn-primary rounded-pill px-4">
        Save
    </button>

    <a href="{{ route('institutes.index') }}"
       class="btn btn-secondary rounded-pill px-4">
        Back
    </a>

</div>