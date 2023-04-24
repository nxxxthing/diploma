
# some more ls aliases
alias ll='ls -alF'
alias la='ls -A'
alias l='ls -CF'


# Custom user scripts ----------------------------
alias mkdir='mkdir -p'
alias rm='rm -rf'

alias mkcd="mkcd"
mkcd(){
    mkdir "$1" -p
    cd "$1"
    pwd
}

# Composer
alias pcomposer='composer'


# GIT ALIACES
alias gs='git status '
alias ga='git add '
alias gb='git branch'
alias gc='git commit'
alias gd='git diff'
alias go='git checkout '
alias gk='gitk --all&'
alias got='git '
alias get='git '
alias gh='git hist'
alias gac='git add . && git commit -am '

# PHP, Laravel development
_artisan()
{
    local arg="${COMP_LINE#php }"

    case "$arg" in
        a*)
            COMP_WORDBREAKS=${COMP_WORDBREAKS//:}
            COMMANDS=`artisan --raw --no-ansi list | sed "s/[[:space:]].*//g"`
            COMPREPLY=(`compgen -W "$COMMANDS" -- "${COMP_WORDS[COMP_CWORD]}"`)
            ;;
        *)
            COMPREPLY=( $(compgen -o default -- "${COMP_WORDS[COMP_CWORD]}") )
            ;;
        esac

    return 0
}
complete -F _artisan artisan
complete -F _artisan a
complete -F _artisan php

alias artisan='php artisan'
alias a='php artisan'
alias phpunit='./vendor/bin/phpunit'

# Shortcuts
# Artisan
alias pao='php artisan optimize'
alias pac='php artisan optimize:clear'
alias pam='php artisan migrate'
alias pamr='php artisan migrate:rollback'
alias pamf='php artisan migrate:fresh'
alias pamfs='php artisan migrate:fresh --seed'

# npm
alias nrw="npm run watch"
alias nrd="npm run dev"
alias nrp="npm run prod"
alias ni="npm install"
alias nu="npm update"


# ide helper
alias ih="artisan ide-helper:generate && artisan ide-helper:meta && artisan ide-helper:models -N"
alias ihm="artisan ide-helper:models -N"

alias upd_s="source ~/.bashrc"
