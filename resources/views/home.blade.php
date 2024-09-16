<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
        /* Custom styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-button {
            margin-right: 10px;
        }
           /* Custom Pagination Styles */
           .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0;
            list-style: none;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-link {
            display: inline-block;
            padding: 5px 10px; /* Smaller padding */
            font-size: 0.8rem; /* Smaller font size */
            border-radius: 3px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination .page-link:hover {
            background-color: #ddd;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            border-color: #ddd;
            cursor: not-allowed;
        }
    </style>
</head>

<body style="margin-left:30%;">
    <div class="container" style="width: 60%;">
        <h1>Contact List</h1>

        <div style="display: flex; flex-direction: column; align-items: flex-end; padding: 1em;">
    <div style="display: flex; align-items: center; margin-bottom: 1em;">
        <a href="{{ route('addContanct') }}" style="margin-right: 2em; text-decoration: none; color: #007bff; font-weight: bold; font-size: 1em; padding: 0.5em 1em; border-radius: 4px; border: 1px solid #007bff; transition: background-color 0.3s, color 0.3s;">Add Contact</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" style="background-color: #dc3545; border: none; color: white; padding: 0.5em 1em; border-radius: 4px; font-size: 1em; cursor: pointer; transition: background-color 0.3s;">
                Logout
            </button>
        </form>
    </div>

    <input type="text" id="search" placeholder="Search contact..." style="width: 100%; max-width: 300px; padding: 0.5em; border: 1px solid #ccc; border-radius: 4px; font-size: 1em; margin-top: 1em;">
</div>


  

        <table id="contactTable" style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 16px; color: #333;">
    <thead style="background-color: #f4f4f4; border-bottom: 2px solid #ddd;">
        <tr>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Name</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Company</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Phone</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Email</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contacts as $contact)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 12px;">{{ $contact->name }}</td>
            <td style="padding: 12px;">{{ $contact->company }}</td>
            <td style="padding: 12px;">{{ $contact->phone }}</td>
            <td style="padding: 12px;">{{ $contact->email }}</td>
            <td style="padding: 12px;">
                <a href="{{ route('contact.edit', $contact->id) }}" style="text-decoration: none; color: #007bff;">Edit</a>
                <form action="{{ route('contact.destroy', $contact->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('POST')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')" style="border: none; background: none; color: #dc3545; cursor: pointer; font-size: 14px;">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


        <div id="pagination" style="width: 100%; display:flex; ">
            {{ $contacts->links() }}
        </div>

                <!-- The Modal -->
                <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Are you sure you want to delete this contact?</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-button">Yes</button>
                    <button type="button" class="modal-button close-button">No</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                if (confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                           
                            form.closest('tr').remove();
                            alert('Contact deleted successfully.');
                        },
                        error: function(xhr) {
                            alert('An error occurred while deleting the contact.');
                        }
                    });
                }
            });

            $('#search').on('keyup', function() {
    let query = $(this).val();

    $.ajax({
        url: '{{ route('contact.search') }}',
        type: 'GET',
        data: { query: query },
        success: function(response) {
            let rows = '';
            if (response.length > 0) {
                $.each(response, function(index, contact) {
                    rows += '<tr style="border-bottom: 1px solid #ddd;">';
                    rows += '<td style="padding: 12px;">' + contact.name + '</td>';
                    rows += '<td style="padding: 12px;">' + contact.company + '</td>';
                    rows += '<td style="padding: 12px;">' + contact.phone + '</td>';
                    rows += '<td style="padding: 12px;">' + contact.email + '</td>';
                    rows += '<td style="padding: 12px;">';
                    rows += '<a href="/contact/' + contact.id + '/edit" style="text-decoration: none; color: #007bff;">Edit</a> ';
                    rows += '<form action="/contact/' + contact.id + '" method="POST" style="display:inline;">';
                    rows += '@csrf @method("DELETE")'; // This will be evaluated on the server side
                    rows += '<button type="submit" onclick="return confirm(\'Are you sure you want to delete this contact?\')" style="border: none; background: none; color: #dc3545; cursor: pointer; font-size: 14px;">Delete</button></form>';
                    rows += '</td>';
                    rows += '</tr>';
                });
            } else {
                rows = '<tr style="border-bottom: 1px solid #ddd;"><td colspan="5" style="padding: 12px; text-align: center; color: #666;">No results found</td></tr>';
            }
            $('#contactTable tbody').html(rows);
        },
        error: function(xhr) {
            alert('An error occurred while searching.');
        }
    });
});

                 // Handle pagination links
                 $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                let query = $('#search').val();

                $.ajax({
                    url: '{{ route('contact.search') }}',
                    type: 'GET',
                    data: { query: query, page: page },
                    success: function(response) {
                        let rows = '';
                        if (response.contacts.length > 0) {
                            $.each(response.contacts, function(index, contact) {
                                rows += '<tr>';
                                rows += '<td>' + contact.name + '</td>';
                                rows += '<td>' + contact.company + '</td>';
                                rows += '<td>' + contact.phone + '</td>';
                                rows += '<td>' + contact.email + '</td>';
                                rows += '<td><a href="/contact/' + contact.id + '/edit">Edit</a> ';
                                rows += '<form action="/contact/' + contact.id + '" method="POST" style="display:inline;">';
                                rows += '@csrf @method("DELETE")';
                                rows += '<button type="submit" onclick="return confirm(\'Are you sure you want to delete this contact?\')">Delete</button></form></td>';
                                rows += '</tr>';
                            });

                            // Update pagination links
                            let pagination = '';
                            if (response.pagination.last_page > 1) {
                                pagination += '<nav aria-label="Page navigation"><ul class="pagination">';
                                for (let i = 1; i <= response.pagination.last_page; i++) {
                                    pagination += `<li class="page-item ${response.pagination.current_page === i ? 'active' : ''}">
                                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                                    </li>`;
                                }
                                pagination += '</ul></nav>';
                            }

                            $('#contactTable').html(rows);
                            $('#pagination').html(pagination);
                        } else {
                            $('#contactTable').html('<tr><td colspan="5">No results found</td></tr>');
                            $('#pagination').empty();
                        }
                    },
                    error: function(xhr) {
                        $('#contactTable').html('<tr><td colspan="5">An error occurred while searching.</td></tr>');
                        $('#pagination').empty();
                    }
                });
            });
        });
    </script>
</body>

</html>