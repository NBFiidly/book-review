@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>

    <form method="GET" action="{{ route('books.index') }} ">
        <div class="mb-6 flex flex-wrap gap-4">
            <div>
                <label for="title" class="block mb-2 font-medium">Title</label>
                <input type="text" name="title" id="title" placeholder="search by title" value="{{ request('title') }}"
                    class="input" />
            </div>
            <div>
                <label for="author" class="block mb-2 font-medium">Author</label>
                <input type="text" name="author" id="author" placeholder="search by author" value="{{ request('author') }}" class="input" />
            </div>
            <div class="self-end">
                <button type="submit" class="btn">Search</button>
                <a href="{{ route('books.index') }}" class="btn">Reset</a>
            </div>
        </div>
    </form>

    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-1 sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">by {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{ number_format($book->reviews()->avg('rating'), 1) }}
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
