<?php
    if(!User::isLogged())
    {
?>
<div class="col-md-12" id="registrace">
    <div class="brownPanel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Registrace</h3>
        </div>
        
        <div class="panel-body" id="registerBody">
            <form method="post" data-toggle="validator" role="form" id="registerForm" class="col-lg-6 col-lg-offset-6 col-md-8 col-md-4 col-sm-10 col-sm-offset-2 col-xs-12">
                <div class="form-group">
                    <label for="exampleInputEmail1">Název účtu</label>
                    <input type="text" class="form-control" name="accountName" placeholder="Account" required>
                </div>
                
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="emailAddress" placeholder="Email@adress.cz" data-error="Blah, tato emailová adresa je nevalidní!" required>
                    <div class="help-block with-errors"></div>
                </div>
                
                <div class="form-group">
                    <label for="exampleInputEmail1">Heslo</label>
                    <input type="password" class="form-control" name="password" id="registerPassword" placeholder="******" data-minlength="6" required>
                </div>
                
                <div class="form-group">
                    <label for="exampleInputEmail1">Heslo (opakovaně)</label>
                    <input type="password" class="form-control" name="password2" placeholder="******" data-match="#registerPassword" data-match-error="Whoops, hesla se neshodují" required>
                    <div class="help-block with-errors"></div>
                </div>
                
                <div class="form-group">
                    <input type="checkbox" required> Souhlasím s pravidly serveru a zároveň prohlašuji, že mi bylo více než 18let či souhlas zákonného zástupce.
                </div>
                
                <div class="form-group">
                    <input type="submit" value="Registrovat" class="form-control">
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    }
?>

<div class="col-md-12">
    <div class="brownPanel panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Jak se připojit</h3>
        </div>
        
        <div class="panel-body">
            <p>Pro hraní na našem serveru je potřebné vlastnit WoW 4.3.4 a naši úpravu pro clienta. V jednotlivých bodech se vám pokusíme popsat jak se připojit.</p>
            
            <ol>
                <li>Prvním krokem by mělo být pořízení si vhodné verze clienta hry.
                    <ul>
                        <li>Windows verze - kompletní (<a href="https://mega.nz/#F!1tRx1DzY!pZdWDvDfOKo-P8jb-PSxLw">Mega.co.nz</a>, <a href="http://www.itheria.cz/WoWkestazeni/Win.torrent">torrent</a>)</li>
                        <li>Windows verze - mini (<a href="https://webshare.cz/file/15r3d45lw1/world-of-warcraft-cataclysm-zip">Webshare</a>)</li>
                        <li>Mac verze (<a href="http://www.itheria.cz/WoWkestazeni/Mac.torrent">torrent</a>)</li>
                    </ul>
                </li>
                <li>Krokem dva je úprava realmlistu. Naleznete jej ve složce <kbd>/Data/enGB/</kbd> nebo <kbd>/Data/enUS</kbd>. Jméno souboru je <kbd>realmlist.wtf</kbd>.<br>Jeho správné znění je <kbd><?php echo SERVER_REALMLIST; ?></kbd>.</li>
                <li>Dalším krokem je aplikace patchů. Vložíme do adresáře <kbd>/Data/Cache</kbd> př. <kbd>E:/World of Warcraft Cataclysm/Data/Cache/</kbd>
                    <ul>
                        <li>Windows
                            <ul>
                                <li><a href="http://www.itheria.cz/download/patchedohry/SoundCache-patch-15595.MPQ">SoundCache-patch-15595.MPQ</a></li>
                                <li><a href="http://www.itheria.cz/download/patchedohry/SoundCache-0.MPQ">SoundCache-0.MPQ</a></li>
                                <li><a href="http://www.itheria.cz/download/patchedohry/Data.rar">Patch 1.1</a></li>
                            </ul>
                        </li>
                        <li>Mac
                            <ul>
                                <li><a href="http://www.itheria.cz/download/patchedohry/wow-update-base-20000.MPQ">wow-update-base-20000.MPQ</a></li>
                                <li><a href="http://www.itheria.cz/download/patchedohry/wow-update-base-20001.MPQ">Patch 1.1</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>Posledním krokem je stáhnutí upraveného WoW.exe
                    <ul>
                        <li><a href="http://www.itheria.cz/download/Itheria.exe">Mac verze</a></li>
                        <li><a href="http://www.itheria.cz/download/Itheria(win).exe">Windows verze</a></li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="brownPanel panel panel-default" data-panel='1'>
        <div class="panel-heading">
            <h3 class="panel-title">Mac WoW ve Windows <a href="#" class="pull-right togglePanel">+</a></h3>
        </div>
        
        <div class="panel-body">
            <p>Mac klient WoW hry je doporučenou metodou, kvůli nejlepší kompatibilitě s patchi. Jejich přidávání je nejsnadnější a jeho použití je nutností pro všechny členy GM teamu. Pro snadnou práci doporučujeme využít.</p>
            
            <ol>
                <li>Pro hraní na našem serveru je potřeba vlastnit klienta hry. Mac klienta hry naleznete v torrentové podobě ke stažení o panel výše</li>
                <li>Po stažení torrentu WoWko umístěte do vámi zvolené složky a pozice, která pro vás bude výchozí. Samotná složka pro naše účely bude označena jako WoW Itheria a příkladem může být uložení: <kbd>C:/WoW Itheria/</kbd></li>
                <li>Nyní je potřeba stáhnout patche. A ty uložit do příslušného místa. U Mac verze tomu je ve složce <kbd>/WoW Itheria/Data/</kbd> a patche jsou ke stažení opět o panel výše</li>
                <li>Po stáhnutí a umíštění patchů je nutno změnit realmlist hry. Ten naleznete ve složce <kbd>/WoW Itheria/Data/enUS/</kbd> nebo <kbd>/WoW Itheria/Data/enGB/</kbd> podle verze hry. Zde se nachází jako <kbd>realmlist.wtf</kbd>. Můžete jej <a href="http://www.itheria.cz/download/realmlist.wtf">stáhnout zde</a> a vložit do složky. Nebo je nutno jej otevřít v editoru a přepsat na tuto adresu: <kbd><?php echo SERVER_REALMLIST; ?></kbd></li>
                <li>Posledník krokem je <a href="http://www.itheria.cz/download/Itheria.exe">stáhnutí exe</a> souboru, který umožní spuštění hry.</li>
            </ol>
            
            <p>Přejeme příjemnou zábavu</p>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="brownPanel panel panel-default" data-panel='2'>
        <div class="panel-heading">
            <h3 class="panel-title">Windows WoW - kompletní <a href="#" class="pull-right togglePanel">+</a></h3>
        </div>
        
        <div class="panel-body">
            <p>Win s plnou podporou je nejčastější verzí hry a to protože Windows WoW je velmi časté. Ovšem na Cataclysmu je jeho editace složitá a patche je vhodné zálohovat.</p>
            
            <ol>
                <li>Pro hraní na našem serveru je potřeba vlastnit klienta hry. Windows klienta hry naleznete v torrentové či MEGA podobě ke stažení o dva panely výše</li>
                <li>Po stažení jej podle potřeby rozbalte nebo umístěte do vámi zvolené složky a pozice, která pro vás bude výchozí. Samotná složka pro naše účely bude označena jako WoW Itheria a příkladem může být uložení: <kbd>C:/WoW Itheria/</kbd></li>
                <li>Nyní je potřeba stáhnout patche. A ty uložit do příslušného místa. U Windows verze tomu je ve složce <kbd>/WoW Itheria/Data/Cache/</kbd> a patche jsou ke stažení opět o  dva panely výše</li>
                <li>Po stáhnutí a umíštění patchů je nutno změnit realmlist hry. Ten naleznete ve složce <kbd>/WoW Itheria/Data/enUS/</kbd> nebo <kbd>/WoW Itheria/Data/enGB/</kbd> podle verze hry. Zde se nachází jako <kbd>realmlist.wtf</kbd>. Můžete jej <a href="http://www.itheria.cz/download/realmlist.wtf">stáhnout zde</a> a vložit do složky. Nebo je nutno jej otevřít v editoru a přepsat na tuto adresu: <kbd><?php echo SERVER_REALMLIST; ?></kbd></li>
                <li>Posledník krokem je <a href="http://www.itheria.cz/download/Itheria(win).exe">stáhnutí exe</a> souboru, který umožní spuštění hry.</li>
            </ol>
            
            <p>Přejeme příjemnou zábavu</p>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="brownPanel panel panel-default" data-panel='3'>
        <div class="panel-heading">
            <h3 class="panel-title">Windows WoW - miniverze (10MB) <a href="#" class="pull-right togglePanel">+</a></h3>
        </div>
        
        <div class="panel-body">
            <p>Win 10 MB je úžasnou věcí pro rychlý přístup. Dokážete rozběhnout hru během stáhnutí patchů. Ovšem musíte počítat s tím, že vše vám bude trvat déle. Data se stahují postupně a funguje stejně jako Win verze hry.</p>
            
            <ol>
                <li>Pro hraní na našem serveru je potřeba vlastnit klienta hry. Mini Windows klienta hry naleznete ke stažení o tři panely výše</li>
                <li>Po stažení jej podle potřeby rozbalte nebo umístěte do vámi zvolené složky a pozice, která pro vás bude výchozí. Samotná složka pro naše účely bude označena jako WoW Itheria a příkladem může být uložení: <kbd>C:/WoW Itheria/</kbd></li>
                <li>Nyní je nutno spustit exe, které se nachází ve složce. Nyní se spustí načítání hry, které po dokončení úspěšně spustí klienta hry. Ten si upravte a vypněte. Opětovně je nutno klienta hry zapnout pro ověření správnosti rozbalení než se bude pokračovat k dalším krokům. V případě funkčnosti postupujte dále na krok 4.</li>
                <li>Nyní je potřeba stáhnout patche. A ty uložit do příslušného místa. U Windows verze tomu je ve složce <kbd>/WoW Itheria/Data/Cache/</kbd> a patche jsou ke stažení opět o  dva panely výše</li>
                <li>Posledník krokem je <a href="http://www.itheria.cz/download/Itheria(win).exe">stáhnutí exe</a> souboru, který umožní spuštění hry.</li>
            </ol>
            
            <p>Přejeme příjemnou zábavu</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function()
    {
        $(".brownPanel[data-panel=1] .panel-body, .brownPanel[data-panel=2] .panel-body, .brownPanel[data-panel=3] .panel-body").hide().hide();
        
        $(".togglePanel").on("click", function(e)
        {
            e.preventDefault();
            
            if($(this).html() == "-")
            {
                $(this).html("+");
                $(this).closest(".brownPanel").children(".panel-body").hide();
            }
            else
            {
                $(this).html("-");
                $(this).closest(".brownPanel").children(".panel-body").show();
            }
            
            //$(this).closest(".brownPanel").children(".panel-body").slideToggle();
        });
    });
</script>

<?php
    View::show("footer");
?>