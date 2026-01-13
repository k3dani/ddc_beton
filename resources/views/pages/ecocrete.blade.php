@extends('layouts.public')

@section('title', 'EcoCrete')

@section('content')
<div class="page-ecocrete">
    <div class="top-block">
        <div class="wrapper">
            <div class="left">
                <div class="logo">
                    <img src="{{ asset('images/ecocretedark.svg') }}" alt="">
                </div>
                <div class="title">
                    <h1>Fenntartható, új generációs beton. A jövő nemzedékek számára.</h1>
                </div>
                <div class="text">
                    <p>A beton a víz után a második legtöbbet használt anyag a világon.</p>
                    <p>Iparágunk fontos szerepet játszik abban, hogy az építőanyagok gyártása ne járjon széndioxid-kibocsátással. A Duna-Dráva Cement Kft. megérti ezt a kihívást, és a HeidelbergMaterials csoport részeként a fenntartható építőanyagokra való áttérés motorja.</p>
                    <p>&nbsp;</p>
                </div>
                <div class="button">
                    <a class="btn" href="{{ route('ecocrete') }}" target="_self">További információk</a>
                </div>
            </div>
            <div class="right">
                <div class="hero-image" style="background: url('http://betoonpluss.k3.hu/wp-content/uploads/2024/01/iStock-175490608.jpg.webp') center / cover no-repeat;">
                </div>
                <div class="circle-block">
                    <div class="cicrle">
                        <div class="top">
                            <p>20%</p>
                        </div>
                        <div class="bottom">
                            <p>kevesebb CO2-kibocsátás</p>
                        </div>
                    </div>
                    <div class="cicrle">
                        <div class="top">
                            <p>100%</p>
                        </div>
                        <div class="bottom">
                            <p>minőség, megbízhatóság, tartósság</p>
                        </div>
                    </div>
                    <div class="cicrle">
                        <div class="top">
                            <p>100%</p>
                        </div>
                        <div class="bottom">
                            <p>helyi eredetű nyersanyagok</p>
                        </div>
                    </div>
                    <div class="cicrle">
                        <div class="top">
                            <p>100%</p>
                        </div>
                        <div class="bottom">
                            <p>újrahasznosítás</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pros-block">
        <div class="wrapper">
            <div class="left">
                <div class="title-row">
                    <div class="logo">
                        <img src="{{ asset('images/ecocretelight.svg') }}" alt="">
                    </div>
                    <div class="title">
                        <h2>Előnyök</h2>
                    </div>
                </div>
                <div class="text">
                    <p>EcoCrete, egy alacsonyabb CO2-kibocsátású betontermék típus.</p>
                </div>
                <div class="list">
                    <ul style="font-weight: 400">
                        <li>Az EcoCrete az Ön minden betonozási munkájához és sokféle szerkezet megépítéséhez alkalmas.</li>
                        <li>A gyártás felügyeletét az LST EN 206 / LST 1974 szabvány szerint tanúsított gyártásellenőrzési rendszerrel végezzük.</li>
                    </ul>
                </div>
            </div>
            <div class="right">
                <div class="list">
                    <ul>
                        <li>
                            <div class="icon" style="background: url('http://betoonpluss.k3.hu/wp-content/uploads/2023/11/co2.svg') center / contain no-repeat;"></div>
                            <p>Akár 20%-kal alacsonyabb CO2-kibocsátás.</p>
                        </li>
                        <li>
                            <div class="icon" style="background: url('http://betoonpluss.k3.hu/wp-content/uploads/2023/11/leaf.svg') center / contain no-repeat;"></div>
                            <p>A termékek magas minőségét, megbízhatóságát és hosszú élettartamát a környezetre gyakorolt negatív hatások nélkül tartjuk fenn.</p>
                        </li>
                        <li>
                            <div class="icon" style="background: url('http://betoonpluss.k3.hu/wp-content/uploads/2023/11/check.svg') center / contain no-repeat;"></div>
                            <p>A megbízhatóságot fenntarthatósági tanúsítványok biztosítják.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="char-block">
        <div class="wrapper">
            <div class="left" style="background: url('http://betoonpluss.k3.hu/wp-content/uploads/2024/01/MicrosoftTeams-image-5.jpg.webp') center / cover no-repeat;"></div>
            <div class="right">
                <div class="title-row">
                    <div class="logo">
                        <img src="{{ asset('images/ecocretedark.svg') }}" alt="">
                    </div>
                    <div class="title">
                        <h2>jellemzők</h2>
                    </div>
                </div>
                <div class="table">
                    <table>
                        <tr>
                            <th>Főbb jellemzők</th>
                        </tr>
                        <tr>
                            <td>Besorolások a környezeti hatás szerint</td>
                            <td>[XC0–XC4]; [XF1–XF4]; [XA1]; [XD1–XD3]; [XS1–XS3]; [XM1–XM3]</td>
                        </tr>
                        <tr>
                            <td>Konzisztencia osztályok</td>
                            <td>S1; S2; S3; S4</td>
                        </tr>
                        <tr>
                            <td>Nyomószilárdsági osztályok</td>
                            <td>C20/25, C25/30, C30/37, C35/45, C45/55</td>
                        </tr>
                        <tr>
                            <td>Maximális kloridtartalom (osztály)</td>
                            <td>Cl 0,20</td>
                        </tr>
                        <tr>
                            <td>Adalékanyagváz max. mérete</td>
                            <td>Max. átm. 16 mm</td>
                        </tr>
                        <tr>
                            <td>Tűzállósági osztály</td>
                            <td>Euro A1</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="info-block">
        <div class="wrapper">
            <div class="left">
                <div class="logo">
                    <img src="{{ asset('images/ecocretedark.svg') }}" alt="">
                </div>
                <div class="title">
                    <h2>Hagyományosan magas minőségű termékeink és a tartósság tökéletes kombinációja kisebb CO2-lábnyom mellett.</h2>
                </div>
            </div>
            <div class="right">
                <div class="owl-carousel-ecocrete owl-theme">
                    <div class="item">
                        <div class="title-row">
                            <div class="logo">
                                <img src="{{ asset('images/ecocretelight.svg') }}" alt="">
                            </div>
                            <div class="title">
                                <h2>Összehasonlítás</h2>
                            </div>
                        </div>
                        <div class="text">
                            <p>Az EcocCrete használata garantálja a CO2-kibocsátás akár 20%-os csökkentését, a minőség 100%-os fenntartása mellett.</p>
                        </div>
                        <div class="image">
                            <img src="http://betoonpluss.k3.hu/wp-content/uploads/2023/11/palyginimas-1.svg" alt="">
                        </div>
                        <div class="bottom-text">
                            <p>Ez egy egyedi, optimalizált receptúrának köszönhető, amely a nyersanyagok szén-dioxid-mentesítésén és a kisebb CO2-lábnyomú cement felhasználásán alapul.</p>
                        </div>
                    </div>
                    <div class="item">
                        <div class="title-row">
                            <div class="logo">
                                <img src="{{ asset('images/ecocretelight.svg') }}" alt="">
                            </div>
                            <div class="title">
                                <h2></h2>
                            </div>
                        </div>
                        <div class="text">
                            <p>Továbbra is magas termelékenység és tartósság</p>
                        </div>
                        <div class="image">
                            <img src="http://betoonpluss.k3.hu/wp-content/uploads/2023/11/palyginimas02.svg" alt="">
                        </div>
                        <div class="bottom-text">
                            <p>Ez egy egyedi, optimalizált receptúrának köszönhető, amely a nyersanyagok szén-dioxid-mentesítésén és a kisebb CO2-lábnyomú cement felhasználásán alapul.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-block" style="background: url('http://betoonpluss.k3.hu/wp-content/uploads/2024/01/photo31.jpg.webp') center / cover no-repeat;">
        <div class="wrapper">
            <div class="logo">
                <img src="{{ asset('images/ecocretelight.svg') }}" alt="">
            </div>
            <h2>További információk:</h2>
            <div class="links">
                <a href="mailto:ddcbeton@duna-drava.hu" class="link">
                    <svg id="Group_795" data-name="Group 795" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" height="29.958" viewBox="0 0 40 29.958">
                        <defs>
                            <clipPath id="clip-path">
                                <rect id="Rectangle_562" data-name="Rectangle 562" width="40" height="29.958" fill="#fff"/>
                            </clipPath>
                        </defs>
                        <g id="Group_795-2" data-name="Group 795" clip-path="url(#clip-path)">
                            <path id="Path_577" data-name="Path 577" d="M23.343,118.252a6.017,6.017,0,0,1-6.686,0L.266,107.325c-.091-.061-.18-.124-.266-.189v17.906a3.681,3.681,0,0,0,3.682,3.682H36.318A3.681,3.681,0,0,0,40,125.042V107.136c-.087.065-.176.129-.267.189Z" transform="translate(0 -98.766)" fill="#fff"/>
                            <path id="Path_578" data-name="Path 578" d="M1.566,6.609,17.957,17.536a3.673,3.673,0,0,0,4.085,0L38.434,6.609A3.509,3.509,0,0,0,40,3.681,3.685,3.685,0,0,0,36.319,0H3.681A3.686,3.686,0,0,0,0,3.683,3.509,3.509,0,0,0,1.566,6.609" fill="#fff"/>
                        </g>
                    </svg>
                    ddcbeton@duna-drava.hu
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
