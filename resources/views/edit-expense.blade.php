<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/527e1aeb21.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
    <title>Document</title>
</head>

<body class="h-full bg-gradient-to-r from-purple-500 to-pink-500">
    @auth
        <div class="flex flex-col">
            <!-- navbar -->
            <nav class="bg-gray-800 p-4">
                <div class="top-0 left-0 ml-4">
                    <p class="text-white text-lg mb-3">Welcome, {{ Auth::user()->name }} </p>
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="bg-blue-500 hover:bg-blue-600 text-sm text-white font-bold py-2 px-4 rounded">
                            Sign Out
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </nav>
            <!-- Edit Screen -->
            <div class="sm:flex gap-6">
                <h1>Edit Your Expense's Detail</h1>
                <form action="/edit-expense/{{ $expense->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                        <input type="text" name="description" id="description" value="{{$expense->description}}"
                            class="mt-1 p-2 border rounded-md w-full">
                    </div>

                    <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                            <input type="number" name="amount" id="amount" step="0.01" value="{{$expense->amount}}"
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                            <input type="text" name="category" id="category" value="{{$expense->category}}"
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <div class="mb-4">
                            <label for="expense_date" class="block text-sm font-medium text-gray-700">Expense Date:</label>
                            <input type="date" name="expense_date" id="expense_date" value="{{$expense->expense_date}}"
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <button type="submit"
                            class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                            Save
                        </button>
                </form>
            </div>

        </div>
    @endauth
</body>

</html>
