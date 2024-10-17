#“Time Tracker” Developer Exercise

## Definition

1) The task is to make a simple time tracker. The user should be able to:
   ● Type the name of the task he is working on and click “start”.
   ● See the timer that is counting how long the task is already taking.
   ● Click Stop to stop working on that task (the timer stops).
   ● Type another name for a different task and click “start” again. The page should start
   counting from the beginning.
   ● On the same page (or other, up to you) user should be able to see the summary of the
   time tracker where it displays how much time I spent on which task, and how much time
   I was working today.
2) Requirements:
   ● Place all the code in Github or Bitbucket
   ● Store it in a Docker container.
   ● Feel free to use your favourite PHP framework, but we use Sympfony, and it will be
   more appreciated, we are looking for a professional thatcan do a smart utilization of
   developing tools. Always keep in mind the SOLID principles.
   ● The data should be stored in any relational database you wish.
   ● The tasks can be recognized by name, so if I type “homepage development” twice
   during one day, spend 2h in the morning and 0.5h in the afternoon, then at the end of
   the day I should see 2.5h near “homepage development”.
   ● Don’t forget the README.md
3) Hints:
   We do not require the page to be beautiful, it can be simplest style, but please make
   them responsive, in the simplest possible way. Remember, mobile first!
4) One step further (optional):
   We love the terminal, so we would appreciate it if you write a PHP script that receives by
   parameter the action (start / end) and the name of the task. And other that have to
   returns a list of all the tasks with their status, start time, end time and total elapsed
   time.
   
## Usage

Run `docker compose up -d --wait`

