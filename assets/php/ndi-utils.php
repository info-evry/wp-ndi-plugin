<?php

if (!function_exists('get_ndi_teams')) {
    function get_ndi_teams()
    {
        global $wpdb;
        $teamsDB = $wpdb->prefix . 'ndi_teams';
        return $wpdb->get_results("SELECT * FROM `$teamsDB`;");
    }
}


if (!function_exists('get_not_completed_teams')) {
    function get_not_completed_teams()
    {
        global $wpdb;
        $teamsDB = $wpdb->prefix . 'ndi_teams';
        $membersDB = $wpdb->prefix . 'ndi_members';
        $teams = $wpdb->get_results("SELECT `id`, `teamName` FROM `$teamsDB`;");
        $members = $wpdb->get_results("SELECT * FROM `$membersDB`;");
        $not_completed_teams = array();
        foreach ($teams as $team) {
            $count = 0;
            foreach ($members as $member) {
                $count += $member->teamid === $team->id ? 1 : 0;
            }
            if ($count < 15) {
                $team->remaining = 15 - $count;
                $not_completed_teams[] = $team;
            }
        }
        return $not_completed_teams;
    }
}

if (!function_exists('get_ndi_members')) {
    function get_ndi_members()
    {
        global $wpdb;
        $membersDB = $wpdb->prefix . 'ndi_members';
        return $wpdb->get_results( "SELECT * FROM `$membersDB`;" );
    }
}
if (!function_exists('get_strict_members')) {
    function get_strict_members()
    {
        global $wpdb;
        $membersDB = $wpdb->prefix . 'ndi_members';
        return $wpdb->get_results( "SELECT `id`, `firstname`, `lastname`, `teamid` FROM `$membersDB`;" );
    }
}

if (!function_exists('add_ndi_team')) {
    function add_ndi_team( $tname, $tdesc )
    {
        global $wpdb;
        $teamsDB = $wpdb->prefix . 'ndi_teams';
        if ( !team_exists( $tname ) ) {
            $wpdb->insert(
                $teamsDB,
                array(
                    'teamName' => $tname,
                    'teamDesc' => $tdesc,
                    'date' => date('Y-m-d H:i:s')
                ),
                array(
                    '%s',
                    '%s',
                    '%s'
                )
            );
            $insertedId = (int) $wpdb->insert_id;
            return $insertedId > 0 ? $insertedId : false;
        }
        return false;
    }
}


if (!function_exists('get_team_id')) {
    function get_team_id($tname)
    {
        global $wpdb;
        $teamsDB = $wpdb->prefix . 'ndi_teams';
        $teamName =  $tname ;
        return (int) $wpdb->get_var( $wpdb->prepare( "SELECT `id` FROM `$teamsDB` WHERE `teamName` = '%s';", $teamName ) );
    }
}

if (!function_exists('get_member_id')) {
    function get_member_id($fname, $lname)
    {
        global $wpdb;
        $membersDB = $wpdb->prefix . 'ndi_members';
        $firstname =  $fname ;
        $lastname =  $lname ;
        return (int) $wpdb->get_var( $wpdb->prepare( "SELECT `id` FROM `$membersDB` WHERE `firstname` = '%s' AND `lastname` = '%s';", $firstname, $lastname ) );
    }
}

if (!function_exists('team_exists')) {
    function team_exists( $tname )
    {
        global $wpdb;
        $teamsDB = $wpdb->prefix . 'ndi_teams';
        $teamName =  $tname ;
        return ( (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `$teamsDB` WHERE `teamName` = '%s';", $teamName ) ) ) > 0;
    }
}

if (!function_exists('member_exists')) {
    function member_exists($fname, $lname)
    {
        global $wpdb;
        $firstname =  $fname ;
        $lastname =  $lname ;
        $membersDB = $wpdb->prefix . 'ndi_members';
        return ( (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `$membersDB` WHERE `firstname` = '%s' AND `lastname` = '%s';", $firstname, $lastname ) ) ) > 0;
    }
}


if (!function_exists('add_ndi_member')) {
    function add_ndi_member($firstname, $lastname, $email, $fooddiet, $baclevel, $ismanager, $id)
    {
        global $wpdb;
        $membersDB = $wpdb->prefix . 'ndi_members';
        
        if (!member_exists($firstname, $lastname)) {
            $wpdb->insert(
                $membersDB,
                array(
                    'firstname'     => $firstname,
                    'lastname'      => $lastname,
                    'email'         => $email,
                    'fooddiet'      => $fooddiet,
                    'baclevel'      => $baclevel,
                    'ismanager'     => $ismanager,
                    'teamid'        => $id,
                    'date'          => date('Y-m-d H:i:s')
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                )
            );
            $insertedId = (int) $wpdb->insert_id;
            return $insertedId > 0 ? $insertedId : false;
        }
        return false;
    }
}
