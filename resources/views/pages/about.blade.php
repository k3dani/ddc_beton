@extends('layouts.public')

@section('title', 'Magunkról')

@section('content')
<div class="page page-about">
    <div class="top-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 left">
                    <h1>Magunkról</h1>
                    <div class="text">
                        <p>A DDC Betonrendelés weboldala egy egyszerű és kényelmes módja annak, hogy nem szerződött partnereink betont rendeljenek. A rendelési felületet úgy alakítottuk ki, hogy megfeleljen vevőink igényeinek, és az ügyfelek zökkenőmentes kiszolgálására összpontosíthassunk.</p>
                        <p>A DDC Betonrendelés a Duna-Dráva Cement Kft. tulajdona.</p>
                    </div>
                    <ul>
                        <li>
                            <h3></h3>
                            <p>Szeretnénk megkönnyíteni a beton megrendelését vevőink számára – a termék kiválasztásától, a legmegfelelőbb időpontban végzett szállításon át az egyszerű fizetésig.</p>
                        </li>
                        <li>
                            <h3></h3>
                            <p>Garantáljuk termékeink minőségét. Termékeink tanúsítvánnyal rendelkeznek, és a gyártás során további minőség-ellenőrzésen esnek át.</p>
                        </li>
                        <li>
                            <h3></h3>
                            <p>Fontos számunkra a környezeti fenntarthatóság, ezért termékportfólónkban alacsonyabb környezeti lábnyomú betontermékeket (evoBuild) is kínálunk.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 right">
                    <div class="photo pad-100" style="background: url('/images/ddc-beton.jpg') center / cover no-repeat;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="about-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 left">
                    <div class="photo pad-120" style="background: url('/images/heidelberg-materials.jpg') center / cover no-repeat;"></div>
                </div>
                <div class="col-md-6 right">
                    <h2>Tulajdonosi háttér</h2>
                    <div class="text">
                        <p>A Duna-Dráva Cement Kft. <strong>két németországi székhelyű építőanyag-ipari társaság</strong>, a Heidelberg Materials és a Baustoffgruppe Schwenk 50-50 százalékos <strong>tulajdonában</strong> van.</p>
                        <p>A <strong>Heidelberg Materials</strong> a világ egyik legnagyobb integrált építőanyag-gyártó és szolgáltató vállalata, amely vezető pozícióval rendelkezik a kavics, a cement és a transzportbeton területein. Kiváló működése és a változások iránti nyitottsága révén megközelítőleg 51 ezer alkalmazottal, a több mint 50 országra kiterjedő, 3 ezernél is több gyártási helyszínén nyújt megbízható, hosszú távú pénzügyi teljesítményt. Tevékenységei középpontjában a környezet iránt mutatott felelősségvállalás áll. A karbonsemlegesség felé vezető út éllovasaként a Heidelberg Materials a jövő számára alkot építőanyag megoldásokat. A digitalizáció révén új lehetőségeket biztosít partnerei számára.</p>
                        <p>Az 1847-ben alapított, családi tulajdonban lévő <strong>Baustoffgruppe Schwenk</strong> tevékenysége és szolgáltatási köre a cementgyártáson kívül az építőanyag-ipar több ágazatát is lefedi. Számos cementgyárat működtet Németországban és transzportbeton üzletággal is rendelkezik. A vállalatnak az Ohorongo Cement Ltd.-nél, Namibia első cementgyártó társaságánál is vannak érdekeltségei.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="partner-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2>Legyen partnerünk</h2>
                    <p>Ön tapasztalt kivitelező szakember?<br><br>Ismerje meg, milyen előnyöket kínálunk partnereinknek!</p>
                </div>
                <div class="col-md-6 align-center">
                    <a class="btn" href="#" target="_self">Regisztrálok</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
