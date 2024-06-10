<!-- resources/views/notes/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center my-4">My Notes</h2>
        <button class="btn btn-primary btn-sm">
            <a href="{{ route('notes.create') }}" style="color: white;">Add Note</a>
        </button>
        <!-- Notes Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="noteList">
                <!-- Dynamic content will be injected here by JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch notes from the server
            fetch("{{ route('notes.index') }}", {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': 'Bearer ' + (localStorage.getItem('authToken') || '')
                },
                credentials: 'include' // Important for session-based auth
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const notes = data.notes;
                const noteList = document.getElementById('noteList');
                notes.forEach(note => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${note.id}</td>
                        <td>${note.content}</td>
                        <td>
                        <a href="/notes/${note.id}/edit" class="btn btn-primary btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteNote(${note.id})">Delete</button>
                        </td>
                    `;
                    noteList.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching notes:', error);
                if (error.message.includes('Network response was not ok')) {
                    alert('Failed to fetch notes. Please log in again.');
                    window.location.href = '/login';
                }
            });
        });

        // Function to handle note editing
        function editNote(id, content) {
            const newContent = prompt("Edit the content of the note:", content);
            if (newContent !== null && newContent !== content) {
                fetch(`{{ url('/notes') }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer ' + (localStorage.getItem('authToken') || '')
                    },
                    body: JSON.stringify({ content: newContent }),
                    credentials: 'include'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status) {
                        alert('Note updated successfully');
                        location.reload(); // Reload the page to show the updated content
                    } else {
                        alert('Failed to update note: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating note:', error);
                    alert('Error updating note: ' + error.message);
                });
            }
        }

        // Function to handle note deletion
        function deleteNote(id) {
            if (confirm("Are you sure you want to delete this note?")) {
                fetch(`/notes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': 'Bearer ' + (localStorage.getItem('authToken') || '')
                    },
                    credentials: 'include'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status) {
                        alert('Note deleted successfully');
                        location.reload(); // Reload the page to remove the deleted note
                    } else {
                        alert('Failed to delete note: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting note:', error);
                    alert('Error deleting note: ' + error.message);
                });
            }
        }
    </script>
@endsection
