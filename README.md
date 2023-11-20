# TDK - Proof of Concept

Ease the TYPO3 Composer based Contribution Setup.
Run `composer create-project ochorocho/tdk <target-folder-name>` and answer
the command prompts as needed. If you want the current dev version add `--stability=dev`
to the command.

This command guides you through the process of setting up a
composer based TYPO3 CoreDev environment.

## Command prompts

* What is your TYPO3/Gerrit Account Username? : Username used on https://review.typo3.org/ 
  which in most cases is your http://my.typo3.org login 
* Setup Commit Message Hook? [y/n] : default=y
* Setup Pre Commit Hook? [y/n] : default=y
* Create a basic ddev config? [y/n] : default=y

## Additional Composer commands/scripts

`composer <command>`

* `tdk:setup`: Setup everything to run a Composer based CoreDev Setup
* `tdk:cleanup`: Delete all files and folder
* `tdk:hooks <create|delete>`: Create/delete created hooks in `.git/hooks`
* `tdk:git template`: Configure TYPO3 repository to use `.gitmessage.txt` as commit message template
* `tdk:ddev`: Create a basic ddev configuration
* `tdk:help`: Show summary with links to the TYPO3 Contribution Guide
* `tdk:doctor`: Show potential issues
* `tdk:git apply`: Apply Gerrit patch e.g. `composer tdk:git apply --ref=refs/changes/60/69360/6` 
