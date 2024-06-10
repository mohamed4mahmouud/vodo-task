@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center my-4">Edit Note</h2>

    <!-- Edit Note Form -->
    <form id="editNoteForm" >
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" class="form-control" rows="5" required>{{ $note->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Note</button>
    </form>
</div>
<script>

    function updateNote() {
        const content = document.getElementById('content').value;

        fetch('{{ route("notes.update", $note->id) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Authorization': 'Bearer ' + localStorage.getItem('authToken')
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert('Note updated successfully!');
                window.location.href = '/notes';
            } else {
                alert('Failed to update note: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    document.getElementById('editNoteForm').addEventListener('submit', function(event) {
        event.preventDefault();
        updateNote();
    });
</script>
@endsection
