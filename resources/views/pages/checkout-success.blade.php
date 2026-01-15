@extends('layouts.public')

@section('title', 'Sikeres rendelés')

@section('content')
<div class="page page-checkout-success">
    <div class="container-fluid" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div style="background: #fff; padding: 60px 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                    <div style="width: 80px; height: 80px; background: #004E2B; border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    
                    <h1 style="color: #004E2B; margin-bottom: 25px; font-size: 28px;">Köszönjük megrendelését!</h1>
                    
                    <p style="font-size: 18px; line-height: 1.8; color: #333; margin-bottom: 30px;">
                        Megrendelését továbbítottuk a központnak.<br>
                        Kollégánk hamarosan felveszi Önnel a kapcsolatot.
                    </p>

                    <div style="padding: 20px; background: #f8f9fa; margin-bottom: 30px;">
                        <p style="font-size: 14px; color: #666; margin: 0;">
                            A megrendeléssel kapcsolatos információkat<br>
                            a megadott e-mail címre is elküldtük.
                        </p>
                    </div>

                    <a href="{{ route('homepage') }}" style="display: inline-block; padding: 15px 40px; background: #004E2B; color: #fff; text-decoration: none; font-size: 18px; font-weight: 500; transition: all 0.3s;">
                        Vissza a főoldalra
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
