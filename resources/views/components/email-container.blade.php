<body>
    <table role='presentation' class='body bg-grey'>
        <tr>
            <td align='center'>
                <table role="presentation" class='email-container' >
                    {{ $slot }}
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <x-footer
                    :images='$images' 
                    :config='$config'
                    :theme='$theme'>
                </x-footer>
            </td>
        </tr>
    </table>
</body>