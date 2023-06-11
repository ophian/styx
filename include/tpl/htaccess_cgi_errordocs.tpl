# BEGIN s9y
ErrorDocument 404 {PREFIX}{indexFile}
DirectoryIndex {PREFIX}{indexFile}

<Files *.tpl.php>
    Require all denied
</Files>

<Files *.tpl>
    Require all denied
</Files>

<Files *.sql>
    Require all denied
</Files>

<Files *.inc.php>
    Require all denied
</Files>

<Files *.db>
    Require all denied
</Files>

# END s9y
