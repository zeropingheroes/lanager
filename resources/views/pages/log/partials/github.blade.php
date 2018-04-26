<h2>GitHub</h2>

@php
    // TODO: move to helper function
    function var_export_short($data, $return=true)
    {
        $dump = var_export($data, true);

        $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
        $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
        $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties

        if (gettype($data) == 'object') { // Deal with object states
            $dump = str_replace('__set_state(array(', '__set_state([', $dump);
            $dump = preg_replace('#\)\)$#', "])", $dump);
        } else {
            $dump = preg_replace('#\)$#', "]", $dump);
        }

        if ($return===true) {
            return $dump;
        } else {
            echo $dump;
        }
    }
@endphp

<code>
<pre>
{{ var_export_short(['message' => $log->message, 'context' => json_decode($log->context,true)]) }}
</pre>
</code>