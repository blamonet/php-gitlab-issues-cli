<?php
  $server="https://code.yourserver.com";
  $private_key="YOURPRIVATETOKEN";

  $owner_id = 28; // group ownership of project

  class project {
      public $id;
      public $name;
  }

  $list_of_projects = array();

  $issues_api_url = $server . "/api/v3/projects?private_token=" . $private_key . "&per_page=100"; 
  $contents = file_get_contents($issues_api_url); 
  $contents = utf8_encode($contents); 
  $projects = json_decode($contents); 
  foreach ($projects as $project) {
      if ($project->namespace->owner_id == $owner_id) {
          $_project = new project;
          $_project->id = $project->id;
          $_project->name = $project->name;
          array_push($list_of_projects, $_project);
      }
  }

  $number_of_projects = count($list_of_projects);
  for ($i = 0; $i < $number_of_projects; $i++) {
      $ct = 1;
      $page = 1;
      while ($ct > 0) {
          $issues_api_url = $server . "/api/v3/projects/" . $list_of_projects[$i]->id . "/issues?state=opened&private_token=" . $private_key . "&per_page=100&page=" . $page;
          $contents = file_get_contents($issues_api_url); 
          $contents = utf8_encode($contents); 
          $issues = json_decode($contents); 
          foreach ($issues as $issue) {
              echo str_replace(",","",$issue->title);
              echo "," . str_replace(",","",$list_of_projects[$i]->name);
              echo "," . $issue->iid;
            echo "\r\n";
          }
          $ct = count($issues);
          $page++;
      }
  }
?>
