@extends('layouts.appAdmin')

@section('content')
    <div class="dashboard-container">
        <header class="content-header">
            <h1 class="header-icon"><i class="bx bx-home-alt icon"></i></h1>
            <h1>Dashboard</h1>
        </header>
        <main>
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-headr">
                        <h1>{{$usersCount}}</h1>
                        <h1><i class='bx bxs-user-circle' ></i></h1>
                    </div>
                    <div class="card-content">
                        <h3>All Users</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-headr">
                        <h1>{{$storesCount}}</h1>
                        <h1><i class='bx bx-store'></i></h1>
                    </div>
                    <div class="card-content">
                        <h3>Our Stores</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-headr">
                        <h1>{{$productsCount}}</h1>
                        <h1><i class='bx bxs-box'></i></h1>
                    </div>
                    <div class="card-content">
                        <h3>Stores Products</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-headr">
                        <h1>{{$categoriesCount}}</h1>
                        <h1><i class='bx bx-category-alt'></i></h1>
                    </div>
                    <div class="card-content">
                        <h3>Categories</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-headr">
                        <h1>{{$ordersCount}}</h1>
                        <h1><i class='bx bx-receipt'></i></h1>
                    </div>
                    <div class="card-content">
                        <h3>Orders</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-headr">
                        <h1>{{$commentsCount}}</h1>
                        <h1><i class='bx bxs-message-square-dots'></i></h1>
                    </div>
                    <div class="card-content">
                        <h3>Coment Numbers</h3>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
