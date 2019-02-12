<?php

namespace App\Helpers;

use App\Model\Playlist;
use App\Model\PlaylistItem;
use App\Model\Schedule;
use Carbon\Carbon;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ScheduledTasks
{

    /*
     * This function is called periodically which is determined in /app/console/kernel.php, to summarize the data based on their daily calculations
     *
     */



}
