function cekKamus($kata)
{
$result = Kamus::all()->where('kata_dasar', $kata)->count();

return $result == 1 ? true : false;
}

//fungsi untuk menghapus suffix seperti -ku, -mu, -kah, dsb
function Del_Inflection_Suffixes($kata)
{
$kataAsal = $kata;

if (preg_match('/([km]u|nya|[kl]ah|pun)\z/i', $kata)) { // Cek Inflection Suffixes
return preg_replace('/([km]u|nya|[kl]ah|pun)\z/i', '', $kata);
}
return $kataAsal;
}

// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
function Cek_Prefix_Disallowed_Sufixes($kata)
{

if (preg_match('/^(be)[[:alpha:]]+/(i)\z/i', $kata)) { // be- dan -i
return true;
}

if (preg_match('/^(se)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // se- dan -i,-kan
return true;
}

if (preg_match('/^(di)[[:alpha:]]+/(an)\z/i', $kata)) { // di- dan -an
return true;
}

if (preg_match('/^(me)[[:alpha:]]+/(an)\z/i', $kata)) { // me- dan -an
return true;
}

if (preg_match('/^(ke)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // ke- dan -i,-kan
return true;
}
return false;
}

// Hapus Derivation Suffixes ("-i", "-an" atau "-kan")
function Del_Derivation_Suffixes($kata)
{
$kataAsal = $kata;
if (preg_match('/(i|an)\z/i', $kata)) { // Cek Suffixes
$__kata = preg_replace('/(i|an)\z/i', '', $kata);
if (cekKamus($__kata)) { // Cek Kamus
return $__kata;
} else if (preg_match('/(kan)\z/i', $kata)) {
$__kata = preg_replace('/(kan)\z/i', '', $kata);
if (cekKamus($__kata)) {
return $__kata;
}
}
/*– Jika Tidak ditemukan di kamus –*/
}
return $kataAsal;
}

// Hapus Derivation Prefix ("di-", "ke-", "se-", "te-", "be-", "me-", atau "pe-")
function Del_Derivation_Prefix($kata)
{
$kataAsal = $kata;

/* —— Tentukan Tipe Awalan ————*/
if (preg_match('/^(di|[ks]e)/', $kata)) { // Jika di-,ke-,se-
$__kata = preg_replace('/^(di|[ks]e)/', '', $kata);

if (cekKamus($__kata)) {
return $__kata;
}

$__kata__ = Del_Derivation_Suffixes($__kata);

if (cekKamus($__kata__)) {
return $__kata__;
}

if (preg_match('/^(diper)/', $kata)) { //diper-
$__kata = preg_replace('/^(diper)/', '', $kata);
$__kata__ = Del_Derivation_Suffixes($__kata);

if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^(ke[bt]er)/', $kata)) { //keber- dan keter-
$__kata = preg_replace('/^(ke[bt]er)/', '', $kata);
$__kata__ = Del_Derivation_Suffixes($__kata);

if (cekKamus($__kata__)) {
return $__kata__;
}
}
}

if (preg_match('/^([bt]e)/', $kata)) { //Jika awalannya adalah "te-","ter-", "be-","ber-"

$__kata = preg_replace('/^([bt]e)/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}

$__kata = preg_replace('/^([bt]e[lr])/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}

$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^([mp]e)/', $kata)) {
$__kata = preg_replace('/^([mp]e)/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}

if (preg_match('/^(memper)/', $kata)) {
$__kata = preg_replace('/^(memper)/', '', $kata);
if (cekKamus($kata)) {
return $__kata;
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^([mp]eng)/', $kata)) {
$__kata = preg_replace('/^([mp]eng)/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}

$__kata = preg_replace('/^([mp]eng)/', 'k', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^([mp]eny)/', $kata)) {
$__kata = preg_replace('/^([mp]eny)/', 's', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^([mp]e[lr])/', $kata)) {
$__kata = preg_replace('/^([mp]e[lr])/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^([mp]en)/', $kata)) {
$__kata = preg_replace('/^([mp]en)/', 't', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}

$__kata = preg_replace('/^([mp]en)/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}

if (preg_match('/^([mp]em)/', $kata)) {
$__kata = preg_replace('/^([mp]em)/', '', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}
$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}

$__kata = preg_replace('/^([mp]em)/', 'p', $kata);
if (cekKamus($__kata)) {
return $__kata; // Jika ada balik
}

$__kata__ = Del_Derivation_Suffixes($__kata);
if (cekKamus($__kata__)) {
return $__kata__;
}
}
}
return $kataAsal;
}

function stemming($tweet)
{
$tweet = explode(' ', $tweet);
foreach ($tweet as $kata) {
$kataAsal = $kata;
$cekKata = cekKamus($kata);
if ($cekKata) { // Cek Kamus
$kata; // Jika Ada maka kata tersebut adalah kata dasar
} else { //jika tidak ada dalam kamus maka dilakukan stemming
$kata = Del_Inflection_Suffixes($kata);
if (cekKamus($kata)) {
$kata;
}

$kata = Del_Derivation_Suffixes($kata);
if (cekKamus($kata)) {
$kata;
}

$kata = Del_Derivation_Prefix($kata);
if (cekKamus($kata)) {
$kata;
} else {
$kataAsal;
}
}
}
return implode(' ', $tweet);
}
