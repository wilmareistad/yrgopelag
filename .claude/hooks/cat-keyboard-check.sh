#!/bin/bash
# UserPromptSubmit hook: blocks submission if the prompt has 5+ of the same
# character in a row (e.g. "kkkkkk"), which usually means a cat walked on the keyboard.
jq -r '.prompt' | perl -0777 -ne 'exit(!/(.)\1{4,}/s)' && echo '{"decision":"block","reason":"This prompt contains 5+ of the same character in a row (e.g. \"kkkkkk\") — looks like your cat may have walked across the keyboard. If this was intentional, resubmit the prompt to confirm."}' || echo '{}'
