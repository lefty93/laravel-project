<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/527e1aeb21.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
    <title>Expense Tracker</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::to('/assets/img/5_skull_momney.jpg') }}">
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

            <!-- Content -->
            <div class="sm:flex gap-6">
                <!-- Form to input expense record -->
                <div class="sm:ml-4 sm:mt-5 sm:w-[350px] p-3 bg-white shadow-md sm:rounded-md h-[447px]">
                    <h1 class="text-2xl font-semibold mb-4">Add your expense</h1>
                    <form action="/create-expense" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                            <input type="text" name="description" id="description" required
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                            <input type="number" name="amount" id="amount" step="0.01" required
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                            <input type="text" name="category" id="category" required
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <div class="mb-4">
                            <label for="expense_date" class="block text-sm font-medium text-gray-700">Expense Date:</label>
                            <input type="date" name="expense_date" id="expense_date" required
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>

                        <button type="submit"
                            class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                            Save
                        </button>
                    </form>
                </div>

                @if ($expenses->isEmpty())
                    <div class="text-center mt-8 flex items-center">
                        <p class="text-2xl text-gray-700">You haven't added any expenses yet. Start by adding your expenses.
                        </p>
                    </div>
                @else
                    <!-- Table to show expense record -->
                    <div class="sm:mt-5">
                        <div class="mb-4 flex justify-between md:items-center">
                            <h1 class="text-2xl font-semibold">Expense Records</h1>
                            <!--paginator-->
                            <div class="flex items-center justify-center md:justify-end">
                                <ul class="pagination flex flex-row gap-3">
                                    <li>
                                        <a href="{{ $expenses->previousPageUrl() }}" class="pagination-link"
                                            {{ $expenses->previousPageUrl() ? '' : 'disabled' }}>
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </a>
                                    </li>

                                    {{-- @foreach ($expenses->getUrlRange(1, $expenses->lastPage()) as $page => $url)
                                    <li>
                                        <a href="{{ $url }}"
                                            class="pagination-link {{ $page == $expenses->currentPage() ? 'active' : '' }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endforeach --}}

                                    <li>
                                        <a href="{{ $expenses->nextPageUrl() }}" class="pagination-link"
                                            {{ $expenses->hasMorePages() ? '' : 'disabled' }}>
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border sm:rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr class="text-gray-600 uppercase text-sm leading-normal ">
                                        <th class="py-2 px-1 md:px-2">Date</th>
                                        <th class="py-2 px-1 md:px-2">Category</th>
                                        <th class="py-2 px-1 md:px-2">Description</th>
                                        <th class="py-2 px-1 md:px-2">Amount</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white">
                                    <!-- Sample data rows -->
                                    @foreach ($expenses as $expense)
                                        <tr class="text-gray-600 text-sm">
                                            <td class="py-2 px-1 md:px-8 border-b border-gray-200">
                                                {{ $expense['expense_date'] }}
                                            </td>
                                            <td class="py-2 px-1 md:px-8 border-b border-gray-200">
                                                {{ $expense['category'] }}
                                            </td>
                                            <td class="py-2 px-1 md:px-8 border-b border-gray-200">
                                                {{ $expense['description'] }}
                                            </td>
                                            <td
                                                class="py-2 px-1 md:px-2 border-b border-gray-200 group grid grid-cols-2 gap-1">
                                                ${{ $expense['amount'] }}
                                                <!-- Edit & Delete -->
                                                <div class="flex gap-2">
                                                    <a href="/edit-expense/{{ $expense->id }}"
                                                        class="    opacity-0 group-hover:opacity-100 cursor-pointer">
                                                        <i class="fa-solid fa-pencil text-blue-600 hover:text-blue-800"></i>
                                                    </a>
                                                    <form action="/delete-expense/{{ $expense->id }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="absolute opacity-0 group-hover:opacity-100 cursor-pointer">
                                                            <i
                                                                class="fa-solid fa-trash text-red-600 hover:text-red-800"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!--Container to show expense chart -->
                    <div>
                        <h1>Total Expenses: ${{ $totalAmount }} </h1>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-[120px] w-[120px] rounded-full" src="{{ URL::to('/assets/img/5_skull_momney.jpg') }}"
                    alt="Logo">
                <h2 class="mt-1 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign up to track your
                    expenses!</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="/register" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="name" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-2">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                            address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-2">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pl-2">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                            Up</button>
                    </div>
                </form>
            </div>
            <div class="flex justify-center mt-5">
                <p class="text-sm">Already Registered? <a href="/login">Sign In.</a></p>
            </div>
        </div>

    @endauth
</body>

</html>
