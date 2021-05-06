<?php

use App\Models\Abusive;
use App\Models\Kamus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Bobot;
use App\Models\BobotTweet;
use Illuminate\Support\Facades\Http;

function scraping()
{
    $response = Http::withHeaders([
        'x-rapidapi-key' => '96f7afa770msh321de7b73a2fe29p1a72a3jsn725ee221fb1e',
        'x-rapidapi-host' => 'twitter32.p.rapidapi.com'
    ])->get('https://twitter32.p.rapidapi.com/getSearch', [
        'username' => 'cnn',
        'hashtag' => 'trump',
        'start_date' => '2018-01-01',
        'end_date' => '2020-10-10',
        'lang' => 'en'
    ]);

    echo $response->body();
}

function casefolding($tweet)
{
    return Str::lower($tweet);
}

function cleansing($tweet)
{
    $tweet = preg_replace('/(\xF0\x9F[\x00-\xFF][\x00-\xFF])/', ' ', $tweet);
    $tweet = preg_replace('/[0-9,\(\)\-\=\.\,\;\!\?]+/', ' ', $tweet);
    $tweet = preg_replace('/@[-A-Z0-9+&@#""\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_\$]/i', '', $tweet);
    $tweet = preg_replace('/#[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $tweet);
    $tweet = preg_replace('/\b(https?|ftp|file|http):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $tweet);
    $tweet = preg_replace('/rt | a`;/i', '', $tweet);

    $tweet = explode(' ', trim($tweet));
    foreach ($tweet as $kata) {
        $lenkata = strlen($kata);
        if ($lenkata <= 2) {
            $key = array_search($kata, $tweet);
            unset($tweet[$key]);
        }
    }

    return implode(' ', $tweet);
}

function convert_negation($tweet)
{
    $List = array(
        'gak' => 'gak', 'ga', 'ngga' => 'ngga', 'tidak' => 'tidak', 'bkn' => 'bkn',
        'tida' => 'tida', 'tak' => 'tak', 'jangan' => 'jangan', 'enggak' => 'enggak', 'gak' => 'gak',
        'ga' => 'ga', 'ngga' => 'ngga', 'tidak' => 'tidak', 'bkn' => 'bkn', 'tida' => 'tida',
        'tak' => 'tak', 'jangan' => 'jangan', 'enggak' => 'enggak'
    );
    $patterns = array();
    $replacement = array();

    foreach ($List as $from => $to) {
        $from = '/\b' . $from . '\b/';
        $patterns[] = $from;
        $replacement[] = $to;
    }
    return preg_replace($patterns, $replacement, $tweet);
}

function hitung_tf($tweets)
{
    $tweet = $tweets->results;
    $tweet = explode(' | ', trim($tweet));

    foreach ($tweet as $kata) {
        if ($kata != '') {

            $rescount = DB::table('bobots')->select('tf')->where('term', $kata)->where('tweet_id', $tweets->id);
            $nTerm = $rescount->count();

            if ($nTerm > 0) {
                $count = $rescount->value('tf');
                $count++;

                DB::table('bobots')->where('term', $kata)->where('tweet_id', $tweets->id)->update(['tf' => $count]);
            } else {
                $term = new Bobot;

                $term->tweet_id = $tweets->id;
                $term->term = $kata;
                $term->tf = 1;
                $term->save();
            }
        }
    }
}

function hitung_tfidf()
{
    $data = Bobot::all();
    $resdoc = DB::table('bobots')->distinct('tweet_id');
    $nDoc = $resdoc->count();

    foreach ($data as $kata) {
        $term = $kata->term;
        $tf = 1 + log($kata->tf);

        $resNTerm = DB::table('bobots')->select(DB::raw('Count(*) as N'))->where('term', $term)->get();
        $nTerm = $resNTerm[0]->N;

        $idf = log($nDoc / $nTerm);

        $tfidf = $tf * $idf;

        $update_bobot = Bobot::findOrFail($kata->id);
        $update_bobot->df = $nTerm;
        $update_bobot->tfidf = $tfidf;

        $update_bobot->save();
    }
}

function hitung_bobotdoc()
{
    $kasar = Abusive::all();
    for ($i = 1; $i <= 100; $i++) {
        $bobot = array();
        foreach ($kasar as $query) {
            $nQuery = Bobot::where('tweet_id', $i)->where('term', $query->kata)->first();
            if (isset($nQuery)) {
                array_push($bobot, $nQuery->tfidf);
            }
        }
        $docBobot = array_sum($bobot);
        $bobot_baru = new BobotTweet();
        $bobot_baru->tweet_id = $i;
        $bobot_baru->bobot = $docBobot;

        $bobot_baru->save();
    }
}

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

        if (preg_match('/^(ke[bt]er)/', $kata)) {  //keber- dan keter-
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
