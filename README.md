# API Endpoints Docs

## Endpoints for books

### 1. Getting a list of books

- **Method:** GET
- **URL:** `api/books`
- **Parameters:**
- `title` (string) — filter by book title (substring).
  - `description` (string) — filter by book description (substring).
  - `authors` (array of integers) — filter by author ID (multiselect).
  - `languages' (array of strings) — filter by book languages (multiselect).
  - `genres' (array of strings) — filter by genres of books (multiselect).
  - `page_count_min` (integer) — the minimum number of pages.
  - `page_count_max` (integer) — the maximum number of pages.
- **Description:** Returns a list of books with the ability to filter by various parameters.

### 2. Creating a new book

- **Method:** POST
- **URL:** `api/books`
- **Parameters in the request body:**
- `title` (string) — the title of the book.
  - `author` (string) — the name of the author (a new author will be created if not found).
  - `description' (string) — description of the book.
  - `page_count` (integer) — the number of pages.
  - `language' (string) — the language of the book.
  - `genre' (string) — the genre of the book.
- **Description:** Creates a new book and returns information about the book or an error.

## Endpoints for authors

### 1. Getting a list of authors

- **Method:** GET
- **URL:** `api/authors`
- **Parameters:**
- `name' (string) — filter by author's name (substring).
- **Description:** Returns a list of authors with the ability to filter by name.

### 2. Creating a new author

- **Method:** POST
- **URL:** `api/authors`
- **Parameters in the request body:**
- `name` (string) — the author's name.
- **Description:** Creates a new author and returns information about the author or an error.

## Examples of parameters for a multiselect

For a multiselect in GET requests, parameters can be passed as an array or a repetition of a parameter with different values, for example:
- `/books?authors[]=1&authors[]=2&authors[]=3`
- `/books?languages=en&languages=de`
