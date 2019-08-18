#!/bin/bash
#---------------------------------------------------------------------#
# /etc/bash_completion.d/evm_bash_completion                          #
#                                                                     #
# easy virtualbox management bash_completion script   				  #
#                                                                     #
# Script	: evm_bash_completion                                     #
# Version	: 1.0.0                                                   #
# Author	: Yasin KARABULAK <yasinkarabulak@gmail.com>              #
# Date		: 2017-08-30                                              #
#---------------------------------------------------------------------#
_evm_complete() 
{
    local cur prev opts
    COMPREPLY=()
    cur="${COMP_WORDS[COMP_CWORD]}"
    if [ $COMP_CWORD -gt 2 ] ; then
		prev="${COMP_WORDS[1]}"
    else
		prev="${COMP_WORDS[COMP_CWORD-1]}"
    fi
    opts="start pause resume stop show clone status version"
    PARSER="/home/yasin/bin/evm/evm_parser.php"
    
    if [ $COMP_CWORD -eq 1 ] ; then
        COMPREPLY=( $(compgen -W "${opts}" ${cur}) )
    elif [ $COMP_CWORD -eq 2 ] ; then
		case "$prev" in
			"start")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_stopped clear')" ${cur}) )
			;;
			"stop")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_running clear')" ${cur}) )
			;;
			"show")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_running clear')" ${cur}) )
			;;
			"pause")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_running clear')" ${cur}) )
			;;
			"resume")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_paused clear')" ${cur}) )
			;;
			"clone")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_stopped clear')" ${cur}) )
			;;
			*)
			;;
		esac
    elif [ $COMP_CWORD -gt 2 ] ; then
		case "$prev" in
			"start")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_stopped clear')" ${cur}) )
			;;
			"stop")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_running clear')" ${cur}) )
			;;
			"show")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_running clear')" ${cur}) )
			;;
			"pause")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_running clear')" ${cur}) )
			;;
			"resume")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_paused clear')" ${cur}) )
			;;
			"clone")
				COMPREPLY=( $(compgen -W "$(/usr/bin/php "$PARSER" 'get_stopped clear')" ${cur}) )
			;;
			*)
			;;
		esac
    fi
    
	return 0
} &&
complete -F _evm_complete evm
