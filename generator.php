<?php
// Define intervals for different scale types as numbers
$normal_major = [0, 2, 4, 5, 7, 9, 11];
$normal_minor = [0, 2, 3, 5, 7, 8, 10];
$harmonic_minor = [0, 2, 3, 5, 7, 8, 11];
$notes_sharp = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];
$notes_flat = ["C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "Bb", "B"];
// Define note lengths as lengths and as names
$notelengths = [1, 2, 3, 4, 6, 8, 12, 16];
$notelengths_names = ["semiquaver", "quaver", "quaver_dot", "crotchet", "crotchet_dot", "minim", "minim_dot", "semibreve"];
function get_rand_notelength($total, $time_signature, $notetype)
{
    $randnotelength = 0;
    if ($notetype == 1) {
        // if "Crotchets only" was chosen
        return 3;
    } elseif ($notetype == 2) {
        // if "Quavers only" was chosen
        return 1;
    } elseif ($notetype == 3) {
        // if "Crotchets, minims" was chosen
        if ($time_signature == 12) {
			if ($total % $time_signature <5) {
				if (rand(0,1)==0) {
					return 3;
				}
				else {
					return 5;
				}
			} else {
				return 3;
			}
	    } else {
			if ($total % $time_signature <9) {
				if (rand(0,1)==0) {
					return 3;
				}
				else {
					return 5;
				}
			} else {
				return 3;
			}			
		}
    } else {
        if ($time_signature == 12) {
            // if time signature is 3/4
            if ($total % $time_signature == 0) {
                $randnotelength = rand(0, 6);
            } elseif ($total % $time_signature == 11) {
                $randnotelength = 0;
            } elseif ($total % $time_signature == 10) {
                $randnotelength = rand(0, 1);
            } elseif ($total % $time_signature == 9) {
                $randnotelength = rand(0, 2);
            } elseif ($total % $time_signature > 6) {
                $randnotelength = rand(0, 3);
            } elseif ($total % $time_signature > 4) {
                $randnotelength = rand(0, 4);
            } elseif ($total % $time_signature > 0) {
                $randnotelength = rand(0, 5);
            }
        } else {
            // if time signature is 4/4
            if ($total % $time_signature == 0) {
                $randnotelength = rand(0, 7);
            } elseif ($total % $time_signature == 15) {
                $randnotelength = 0;
            } elseif ($total % $time_signature == 14) {
                $randnotelength = rand(0, 1);
            } elseif ($total % $time_signature == 13) {
                $randnotelength = rand(0, 2);
            } elseif ($total % $time_signature > 10) {
                $randnotelength = rand(0, 3);
            } elseif ($total % $time_signature > 8) {
                $randnotelength = rand(0, 4);
            } elseif ($total % $time_signature > 4) {
                $randnotelength = rand(0, 5);
            } elseif ($total % $time_signature > 0) {
                $randnotelength = rand(0, 6);
            }
        }
        return $randnotelength;
    }
}

function in_scale($note, $scale, $scale_type)
{
    $scale_intervals = [];
    if ($scale_type == "major") {
        $scale_intervals = [0, 2, 4, 5, 7, 9, 11];
    } elseif ($scale_type == "minor" or $scale_type == "harmonicminor") {
        $scale_intervals = [0, 2, 3, 5, 7, 8, 10];
    } else {
        $scale_intervals = [0, 2, 4, 5, 7, 9, 11];
    } // if none, keep it a normal major key

    $notenumber = $note % 12;
    $noteoctave = floor($note / 12);
    if ($notenumber < $scale) {
        $notenumber += 12;
    }
    $notenumber = $notenumber - $scale;
    if (in_array($notenumber, $scale_intervals)) {
        // if not in scale
        return true;
    } else {
        return false;
    }
}

// if rests was checked then the melody can have rests, otherwise it cannot
if (isset($_POST['rests'])) {
    $rests = true;
} else {
    $rests = false;
}

function get_next_note($current, $scale, $scale_type, $notetype, $rests)
{
    // generate next note based on previous note, (or starting note generate if this is first note)
    $nextnote = $current;
    $scale_intervals = [];
    if ($scale_type == "major") {
        $scale_intervals = [0, 2, 4, 5, 7, 9, 11];
    } elseif ($scale_type == "minor") {
        $scale_intervals = [0, 2, 3, 5, 7, 8, 10];
    } elseif ($scale_type == "harmonicminor") {
        $scale_intervals = [0, 2, 3, 5, 7, 8, 11];
    } else {
        $scale_intervals = [0, 2, 4, 5, 7, 9, 11];
    } // if none, keep it a normal major key

    $addby = 0;
    if (!$rests) {
        // if "rests" was unchecked, then no rests
        $probability = rand(3, 23);
    } else {
        // else, rests can occur
        $probability = rand(0, 23);
    }
    if ($probability < 3) {
        $nextnote = -1; // a rest
    } else {
        if ($probability < 6) {
            $addby = rand(1, 2);
        } elseif ($probability < 11) {
            $addby = 2;
        }
        if ($probability < 18) {
            $addby = rand(0, 4);
        } elseif ($probability < 21) {
            $addby = rand(5, 8);
        } elseif ($probability < 23) {
            $addby = rand(9, 12);
        } else {
            $addby = rand(13, 16);
        }
        if (rand(0, 1) == 0) {
            $addby = -$addby;
        }
        $nextnote += $addby;
        if (rand(0, 1) == 0) {
            $addorsub = -1;
        } else {
            $addorsub = 1;
        }
        // check if note is in-scale, and if not in-scale increment/decrement until in-scale
        $notenumber = $nextnote % 12;
        $noteoctave = floor($nextnote / 12);
        if ($notenumber < $scale) {
            $notenumber += 12;
        }
        $notenumber = $notenumber - $scale;
        if (!in_array($notenumber, $scale_intervals)) {
            $notenumber += $addorsub;
            $nextnote += $addorsub;
            if (!in_array($notenumber, $scale_intervals)) {
                $notenumber += $addorsub;
                $nextnote += $addorsub;
            }
        }

        $srand = rand(0, 100);

        while ($nextnote < 0) {
            $nextnote += 12;
        }
        while ($nextnote > 35) {
            $nextnote -= 12;
        }
    }
    return $nextnote;
}

// if instrument type was choosen, check that it's valid, if it's not valid or no instrument was choosen. then choose "square"
$validinstrument = false;
if (isset($_POST['instrument'])) {
    if ($_POST['instrument'] == "sawtooth" || $_POST['instrument'] == "sine" || $_POST['instrument'] == "square" || $_POST['instrument'] == "triangle") {
        $validinstrument = true;
    }
}
if ($validinstrument) {
    $instrument = $_POST['instrument'];
} else {
    $instrument = "square";
}

// if volume was choosen, check that it's valid, if it's not valid or no volume was choosen. then choose "medium"
$validvolume = false;
if (isset($_POST['volume'])) {
    if ($_POST['volume'] == "0.6" || $_POST['volume'] == "0.8" || $_POST['volume'] == "1") {
        $validvolume = true;
    }
}
if ($validvolume) {
    $volume = $_POST['volume'];
} else {
    $volume = "0.8";
}

// if time signature was choosen, check that it's any of 3/4 or 4/4, if it's not one of them or no time signature was choosen. then choose a random time signature
$validts = false;
if (isset($_POST['timesignature'])) {
    if (is_numeric($_POST['timesignature'])) {
        if ($_POST['timesignature'] == 12 || $_POST['timesignature'] == 16) {
            $validts = true;
        }
    }
}
if ($validts) {
    $time_signature = $_POST['timesignature'];
} else {
    if (rand(0, 1) == 0) {
        $time_signature = 16;
    } else {
        $time_signature = 12;
    }
}

// if amount of bars was choosen, check if it's 2,4, or 8, if it's not one of them or no bars amount was choosen. then choose "2 bars"
$validbar = false;
if (isset($_POST['baramount'])) {
    if (is_numeric($_POST['baramount'])) {
        if ($_POST['baramount'] == 2 || $_POST['baramount'] == 4 || $_POST['baramount'] == 8) {
            $validbar = true;
        }
    }
}
if ($validbar) {
    $baramount = $_POST['baramount'];
} else {
    $baramount = 2;
}

// if a key was choosen, check that it's valid, if it's not valid or no key was choosen. then choose a random key
$validkey = false;
if (isset($_POST['key'])) {
    if (is_numeric($_POST['key'])) {
        if ($_POST['key'] >= 0 && $_POST['key'] <= 11) {
            $validkey = true;
        }
    }
}
if ($validkey) {
    $thekey = $_POST['key'];
} else {
    $thekey = rand(0, 11);
}

$scaleid = $thekey . "";
$unnatural = 0;
$total = 0;
$scaletype = "flat";

// if scale mood was choosen, check that it's valid, if it's not valid or no scale mood was choosen. then choose a random scale mood
$validnotelength = false;
$notetype = 0;
if (isset($_POST['notetype'])) {
    if (is_numeric($_POST['notetype'])) {
        if ($_POST['notetype'] >= 0 && $_POST['notetype'] <= 3) {
            $validnotelength = true;
            $notetype = $_POST['notetype'];
        }
    }
}

$validmood = false;
if (isset($_POST['scaletype'])) {
    if (is_numeric($_POST['scaletype'])) {
        if ($_POST['scaletype'] >= 0 && $_POST['scaletype'] <= 11) {
            $validmood = true;
        }
    }
}

if ($validmood) {
    $sm = $_POST['scaletype'];
} else {
    $sm = rand(0, 2);
}
if ($sm == 0) {
    $scalemood = "major";
} elseif ($sm == 1) {
    $scalemood = "minor";
    $scaleid = $scaleid . "m";
} else {
    $scalemood = "harmonicminor";
    $scaleid = $scaleid . "m";
}

switch ($scaleid) {
    case "0":
    case "9m":
        $scaletype = "sharp";
        $unnatural = 0;
        break;
    case "1":
    case "10m":
        $scaletype = "flat";
        $unnatural = 5;
        break;
    case "2":
    case "11m":
        $scaletype = "sharp";
        $unnatural = 2;
        break;
    case "3":
    case "0m":
        $scaletype = "flat";
        $unnatural = 3;
        break;
    case "4":
    case "1m":
        $scaletype = "sharp";
        $unnatural = 4;
        break;
    case "5":
    case "2m":
        $scaletype = "flat";
        $unnatural = 1;
        break;
    case "6":
    case "3m":
        $scaletype = "sharp";
        $unnatural = 6;
        break;
    case "7":
    case "4m":
        $scaletype = "sharp";
        $unnatural = 1;
        break;
    case "8":
    case "5m":
        $scaletype = "flat";
        $unnatural = 4;
        break;
    case "9":
    case "6m":
        $scaletype = "sharp";
        $unnatural = 3;
        break;
    case "10":
    case "7m":
        $scaletype = "flat";
        $unnatural = 2;
        break;
    case "11":
    case "8m":
        $scaletype = "sharp";
        $unnatural = 5;
        break;
}
// if tempo type was choosen, check that it's between 30 and 240, if it's not, or no tempo was choosen. then choose a random tempo
$validtempo = false;
$barline = false;
if (isset($_POST['tempo'])) {
    if (is_numeric($_POST['tempo'])) {
        if ($_POST['tempo'] >= 30 && $_POST['tempo'] <= 240) {
            $validtempo = true;
        }
    }
}
if ($validtempo) {
    $tempo = $_POST['tempo'];
} else {
    $tempo = rand(60, 149);
}
// declare the arrays to store melody data
$melody_notes = [];
$melody_notelength = [];
$melody_notelength_length = [];
if ($sm == 0) {
    $lastnote = $thekey + $normal_major[rand(0, 6)] + 12 * rand(0, 3);
    $startingnote = $thekey + $normal_major[rand(0, 6)] + 12 * rand(0, 1);
} elseif ($sm == 1) {
    $lastnote = $thekey + $normal_minor[rand(0, 6)] + 12 * rand(0, 3);
    $startingnote = $thekey + $normal_minor[rand(0, 6)] + 12 * rand(0, 1);
} else {
    $lastnote = $thekey + $harmonic_minor[rand(0, 6)] + 12 * rand(0, 3);
    $startingnote = $thekey + $harmonic_minor[rand(0, 6)] + 12 * rand(0, 1);
}

while ($startingnote > 35) {
    $startingnote -= 12;
}
$octave = floor($startingnote / 12);
$notename = $notes_flat[$startingnote % 12];
if (rand(0, 23) < 3 && $rests) {
    // first note is rest
    $melody_notes[] = -1;
    $randnotelength = get_rand_notelength($total, $time_signature, $notetype);
    $total += $notelengths[$randnotelength];
    if ($total % $time_signature == 0) {
        $barline = true;
    }
    $melody_notelength[] = $notelengths_names[$randnotelength];
    $melody_notelength_length[] = $notelengths[$randnotelength];
} else {
    $melody_notes[] = $startingnote;
    $randnotelength = get_rand_notelength($total, $time_signature, $notetype);
    $total += $notelengths[$randnotelength];
    if ($total % $time_signature == 0) {
        $barline = true;
    }
    $melody_notelength[] = $notelengths_names[$randnotelength];
    $melody_notelength_length[] = $notelengths[$randnotelength];

    $lastnote = $startingnote;
}
$melody_length = $baramount * $time_signature;
while ($total < $melody_length) {
    if ($total % $time_signature == 0 && $barline) {
        $barline = false;
        $melody_notes[] = -2;
        $melody_notelength[] = 0;
        $melody_notelength_length[] = 0;
    } else {
        $barline = true;
        $nnote = get_next_note($lastnote, $thekey, $scalemood, $notetype, $rests);
        if ($nnote >= 0) {
            $lastnote = $nnote;
        }
        $melody_notes[] = $nnote;
        $randnotelength = get_rand_notelength($total, $time_signature, $notetype);
        $total += $notelengths[$randnotelength];
        $melody_notelength[] = $notelengths_names[$randnotelength];
        $melody_notelength_length[] = $notelengths[$randnotelength];
        $octave = floor($nnote / 12);
        if ($nnote >= 0) {
            $notename = $notes_flat[$nnote % 12];
        } else {
            $notename = "rest";
        }
        ///  echo $nnote.": ".$notename.$octave."<br>";
    }
}
?>
    <form method="post" action="">
	    <div class="row form-group">
        <div class="col-md-3 col-lg-2">Tempo: <br class="d-none d-md-block"><input type="number" name="tempo" min="30" max="240" style="width:140px;" placeholder="Tempo in BPM"<?php if ($validtempo) {
            echo " value=\"" . $tempo . "\"";
        } ?>></div><div class="col-md-3 col-lg-2">
        Key: <br class="d-none d-md-block"><select name="key" size="1">
        <option value="random">Random</option>
        <option value="0" <?php if ($validkey && $thekey == 0) {
            echo "selected";
        } ?>>C</option>
        <option value="1" <?php if ($validkey && $thekey == 1) {
            echo "selected";
        } ?>>C#/Db</option>
        <option value="2" <?php if ($validkey && $thekey == 2) {
            echo "selected";
        } ?>>D</option>
        <option value="3" <?php if ($validkey && $thekey == 3) {
            echo "selected";
        } ?>>D#/Eb</option>
        <option value="4" <?php if ($validkey && $thekey == 4) {
            echo "selected";
        } ?>>E</option>
        <option value="5" <?php if ($validkey && $thekey == 5) {
            echo "selected";
        } ?>>F</option>
        <option value="6" <?php if ($validkey && $thekey == 6) {
            echo "selected";
        } ?>>F#/Gb</option>
        <option value="7" <?php if ($validkey && $thekey == 7) {
            echo "selected";
        } ?>>G</option>
        <option value="8" <?php if ($validkey && $thekey == 8) {
            echo "selected";
        } ?>>G#/Ab</option>
        <option value="9" <?php if ($validkey && $thekey == 9) {
            echo "selected";
        } ?>>A</option>
        <option value="10" <?php if ($validkey && $thekey == 10) {
            echo "selected";
        } ?>>A#/Bb</option>
        <option value="11" <?php if ($validkey && $thekey == 11) {
            echo "selected";
        } ?>>B</option>
        </select></div><div class="col-md-3 col-lg-2">
        Scale: <br class="d-none d-md-block"><select name="scaletype" size="1">
        <option value="random">Random</option>
        <option value="0" <?php if ($validmood && $sm == 0) {
            echo "selected";
        } ?>>Major</option>
        <option value="1" <?php if ($validmood && $sm == 1) {
            echo "selected";
        } ?>>Minor</option>
        <option value="2" <?php if ($validmood && $sm == 2) {
            echo "selected";
        } ?>>Harmonic minor</option>
        </select></div> 
		<div class="col-md-3 col-lg-2">
				Notes: <br class="d-none d-md-block"><select name="notetype" size="1">
        <option value="0">Any</option>
        <option <?php if ($validnotelength && $notetype == 1) {
            echo "selected";
        } ?> value="1">Crotchets only</option>
        <option <?php if ($validnotelength && $notetype == 2) {
            echo "selected";
        } ?> value="2">Quavers only</option>
		<option <?php if ($validnotelength && $notetype == 3) {
            echo "selected";
        } ?> value="3">Crotchets, minims</option>
        </select>
		</div>
		</div>
		<div class="row form-group">
        <div class="col-md-3 col-lg-2">
        Time signature: <br class="d-none d-md-block"><select name="timesignature" size="1">
        <option value="0">Random</option>
        <option <?php if ($validts && $time_signature == 12) {
            echo "selected";
        } ?> value="12">3/4</option>
        <option <?php if ($validts && $time_signature == 16) {
            echo "selected";
        } ?> value="16">4/4</option>
        </select></div><div class="col-md-3 col-lg-2">
        Number of bars: <br class="d-none d-md-block"><select name="baramount" size="1">
        <option value="2">2</option>
        <option <?php if ($validbar && $baramount == 4) {
            echo "selected";
        } ?> value="4">4</option>
        <option <?php if ($validbar && $baramount == 8) {
            echo "selected";
        } ?> value="8">8</option>
        </select></div>
		<div class="col-md-3 col-lg-2">
		Instrument: <br class="d-none d-md-block"><select name="instrument" size="1">
        <option <?php if ($validinstrument && $instrument == "square") {
            echo "selected";
        } ?> value="square">Square</option>
        <option <?php if ($validinstrument && $instrument == "sawtooth") {
            echo "selected";
        } ?> value="sawtooth">Sawtooth</option>
        <option <?php if ($validinstrument && $instrument == "sine") {
            echo "selected";
        } ?> value="sine">Sine</option>
        <option <?php if ($validinstrument && $instrument == "triangle") {
            echo "selected";
        } ?> value="triangle">Triangle</option>
        </select>
		</div>
				<div class="col-md-3 col-lg-2">
        Volume: <br class="d-none d-md-block"><select name="volume" size="1">
        <option <?php if ($volume == "0.6") {
            echo "selected";
        } ?> value="0.6">Low</option>
        <option <?php if ($volume == "0.8") {
            echo "selected";
        } ?> value="0.8">Medium</option>
        <option <?php if ($volume == "1") {
            echo "selected";
        } ?> value="1">High</option>
        </select>
		</div>
        </div>
        
        		<div class="row form-group">
		<div class="col-md-3 col-lg-2">
<input type="checkbox" name="rests" id="rests" value="true"<?php if ($rests) {
    echo " checked";
} ?>>
        <label for="rests">Allow rests</label><br>
		</div>
		<div class="col-md-3 col-lg-2">
		<input type="submit" class="btn btn-primary" style="margin-bottom:8px;" name="generate" value="Generate">
		</div>
        </div>
        
        
        
        
        
        
        </form><br>  
        <div id="ie-alert" class="alert alert-warning">
   <strong>Warning:</strong> Music player is not supported in internet explorer. Please use a more modern browser.
</div>
        <div id="output">
        <button class="btn btn-info" id="playsound"><span style="font-family:'Times New Roman';">&#9654;</span> Play</button>

    <img draggable="false" id="tempo-crotchet" src="images/crotchet_up.png"><span id="tempo">=&nbsp; <?php echo $tempo; ?></span>
    
    <div class="sheet-row">
        <img draggable="false" style="left:0px;top:-10px;" src="images/treble_clef.png" />
    <?php
    $xpos = 35; // TODO: shift more bec of key and time signature
    $key_sharp = [-5, 10, -10, 5, 20, 0, 15];
    $key_flat = [17, 0, 20, 7, 27, 10, -3];

    for ($i = 0; $i < $unnatural; $i++) {
        if ($scaletype == "sharp") {
            $yposk = $key_sharp[$i] - 15;
            $unnaturalimg = "sharp";
        } else {
            $yposk = $key_flat[$i] - 25;
            $unnaturalimg = "flat";
        }
        echo '<img draggable="false" style="left:' . $xpos . 'px;top:' . $yposk . 'px;" src="images/' . $unnaturalimg . '.png" />';
        $xpos += 15;
    }
    $xpos += 5;
    if ($time_signature == 12) {
        $ts_image = 3;
    } else {
        $ts_image = 4;
    }
    echo '<img draggable="false" style="left:' . $xpos . 'px;top:0px;" src="images/' . $ts_image . '_time_signature.png" />';
    $xpos += 25;

    $sharporflat = [1, 3, 6, 8, 10, 13, 15, 18, 20, 22, 25, 27, 30, 32, 34];

    $positions_flat = [
        50,
        45,
        45,
        40,
        40,
        35,
        30,
        30,
        25,
        25,
        20,
        20,
        15,
        10,
        10,
        5,
        5,
        0,
        -5,
        -5,
        -10,
        -10,
        15,
        15,
        10,
        5,
        5,
        0,
        0,
        -5,
        -10,
        -10,
        -15,
        -15,
        -20,
        -20,
        -25,
        -30,
        -30,
        -35,
        -35,
        -40,
        -45,
        -45,
        -50,
        -50,
        -55,
        -55,
        -60,
        -65,
        -65,
        -70,
        -70,
        -75,
        -80,
        -80,
        -85,
        -85,
        -90,
        -90,
    ];
    $positions_sharp = [
        50,
        50,
        45,
        45,
        40,
        35,
        35,
        30,
        30,
        25,
        25,
        20,
        15,
        15,
        10,
        10,
        5,
        0,
        0,
        -5,
        -5,
        -10,
        -10,
        15,
        10,
        10,
        5,
        5,
        0,
        -5,
        -5,
        -10,
        -10,
        -15,
        -15,
        -20,
        -25,
        -25,
        -30,
        -30,
        -35,
        -40,
        -40,
        -45,
        -45,
        -50,
        -50,
        -55,
        -60,
        -60,
        -65,
        -65,
        -70,
        -75,
        -75,
        -80,
        -80,
        -85,
        -85,
        -90,
    ];
    $index = 0;
    foreach ($melody_notes as $note) {
        if ($note == -2) {
            echo '<img draggable="false" style="left:' . $xpos . 'px;top:0;" src="images/bar_line.png" />';
        } elseif ($note == -1) {
            echo '<img draggable="false" style="left:' . $xpos . 'px;top:0;" src="images/rest_' . $melody_notelength[$index] . '.png" />';
        } else {
            if ($scaletype == "flat") {
                $ypos = $positions_flat[$note];

                if ($note < 13) {
                    $expos = $xpos + 5;
                    if ($ypos % 10 == 0) {
                        $eypos = $ypos - 10;
                    } else {
                        $eypos = $ypos - 5;
                    }
                    echo '<img draggable="false" style="left:' . $expos . 'px;top:' . $eypos . 'px;" src="images/extra_lines.png" />';
                } elseif ($note >= 32) {
                    $expos = $xpos + 15;
                    if ($ypos % 10 == 0) {
                        $eypos = $ypos + 10;
                    } else {
                        $eypos = $ypos + 5;
                    }
                    echo '<img draggable="false" style="left:' . $expos . 'px;top:' . $eypos . 'px;" src="images/extra_lines.png" />';
                }
            } else {
                $ypos = $positions_sharp[$note];

                if ($note < 14) {
                    $expos = $xpos + 5;
                    if ($ypos % 10 == 0) {
                        $eypos = $ypos - 10;
                    } else {
                        $eypos = $ypos - 5;
                    }
                    echo '<img draggable="false" style="left:' . $expos . 'px;top:' . $eypos . 'px;" src="images/extra_lines.png" />';
                } elseif ($note >= 33) {
                    $expos = $xpos + 15;
                    if ($ypos % 10 == 0) {
                        $eypos = $ypos + 10;
                    } else {
                        $eypos = $ypos + 5;
                    }
                    echo '<img draggable="false" style="left:' . $expos . 'px;top:' . $eypos . 'px;" src="images/extra_lines.png" />';
                }
            }

            if ($note <= 22 && $scaletype == "sharp" or $note <= 21 && $scaletype == "flat") {
                $dir = "up";
                $ypos++;
                $signxpos = $xpos - 10;
                $signypos = $ypos + 15;
            } else {
                $dir = "down";
                $signxpos = $xpos;
                $signypos = $ypos - 11;
            }
            $outofscale = 0;
            if (!in_scale($note, $thekey, $scalemood)) {
                if (in_array($note, $sharporflat)) {
                    $sign = $scaletype;
                } else {
                    $sign = "natural";
                }
                if ($sign == "flat") {
                    $signypos -= 10;
                }
                echo '<img draggable="false" style="left:' . $signxpos . 'px;top:' . $signypos . 'px;" src="images/' . $sign . '.png" />';
            }

            echo '<img draggable="false" style="left:' . $xpos . 'px;top:' . $ypos . 'px;" src="images/' . $melody_notelength[$index] . '_' . $dir . '.png" />';
        }

        $xpos += 40;
        $index++;
    }
    ?>
        <div class="sheet-line"></div>
        <div class="sheet-line"></div>
        <div class="sheet-line"></div>
        <div class="sheet-line"></div>
        <div class="sheet-line"></div>
    </div><div id="lcheck"></div>
    </div>
    <script>
	    contxt = new (window.AudioContext || window.webkitAudioContext)();
        var frequencies = [130.81,138.59,146.83,155.56,164.81,174.61,185.00,196.00,207.65,220.00,233.08,246.94,
261.63,277.18,293.66,311.13,329.63,349.23,369.99,392.00,415.30,440.00,466.16,493.88,
523.25,554.37,587.33,622.25,659.25,698.46,739.99,783.99,830.61,880.00,932.33,987.77];
        $("#playsound").click(function(){
            var melody = <?php echo json_encode($melody_notes); ?>;
            var tempo = <?php echo $tempo; ?> ; var vx=0;
            var arrayLength = melody.length;
            $("#playsound").attr("disabled", true);
            loopMelody();
        });

        var i = 0; 
<?php
$timeout = [];
$timeout[] = 1;
foreach ($melody_notelength_length as $rl) {
    $timeout[] = $rl;
}
$vol = $volume;
if ($instrument == "sine") {
    $vol += 0.4;
}
if ($instrument == "triangle") {
    $vol += 0.2;
}
?>
function playNote(freq,vol,dur) {
    var osc = contxt.createOscillator();
    var gn = contxt.createGain();
    osc.connect(gn);
    osc.type = '<?php echo $instrument; ?>';
    osc.frequency.value = freq;
    gn.connect(contxt.destination);
    gn.gain.value = vol;
    osc.start();
    setTimeout(function () {
        osc.stop();
    }, dur);
}
function loopMelody() {
      var tempo = <?php echo $tempo; ?> ;
      var melody = <?php echo json_encode($melody_notes); ?>;
      var notelength = <?php echo json_encode($melody_notelength_length); ?>;
      var timeoutnotelength = <?php echo json_encode($timeout); ?>;
      var rlength = notelength[i]/4;
	  var tlength = ((60/tempo)*rlength)*1000;
	  tlength=tlength-8;
	  var rlengtht = timeoutnotelength[i]/4;
	  var tlengtht = ((60/tempo)*rlengtht)*1000;
	  if(melody[i]==-2){tlength=2;}
	  playNote(440.00,0,1000); // silent note because without it first note may be delayed
  setTimeout(function() {
      var pitch=melody[i];
      vol = <?php echo $vol; ?>/100; 
	  if(pitch>=0){
	  playNote(frequencies[pitch],vol,tlength); 
	  }
      var lnth = melody.length; 
    i++; 
    if (i < lnth) {       
      loopMelody();       
    }  
    else{i=0;
            $("#playsound").attr("disabled", false);}        
  }, tlengtht)
}
  $(document).ready(function() {
  $(".sheet-line").css({
    'width': ('<?php echo $xpos; ?>' + 'px')
  });
});     

    </script>
