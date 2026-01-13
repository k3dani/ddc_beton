@extends('layouts.public')

@section('title', 'Újdonságok')

@section('content')
<div class="page page-news">
    <div class="top-block">
        <div class="container-fluid">
            <h1>Hírek és újdonságok</h1>
            <p>A legfrissebb információk a DDC-től</p>
        </div>
    </div>
    
    <div class="news-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="news-item">
                        <h2>Hogyan készítsük elő a nyaralót az új szezonra?</h2>
                        <p class="date">2024. április 7.</p>
                        <div class="content">
                            <p>Ahogy melegszik az idő, a kiskert- és nyaralótulajdonosok készülődni kezdenek az új szezonra – mindannyian alig várjuk, hogy a szabadban tölthessük az időt.</p>
                            <p>A DDC azt tanácsolja, hogy előre tervezze meg a kiskert vagy nyaraló kisebb-nagyobb felújítási munkálatait, hogy nyugodtan várhassa a nyarat.</p>
                            <a href="#" class="btn">Tovább olvasom</a>
                        </div>
                    </div>
                    
                    <div class="news-item">
                        <h2>Új ECOBlock méretek elérhetőek</h2>
                        <p class="date">2024. március 15.</p>
                        <div class="content">
                            <p>Bővítettük az ECOBlock termékcsaládunkat új méretekkel, hogy még szélesebb körben felhasználhatók legyenek építési projektjeiben.</p>
                            <a href="#" class="btn">Tovább olvasom</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
