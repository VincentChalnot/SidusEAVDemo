# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
common_dir = "src/Demo/LayoutBundle/Resources/"

http_path = "web"
sass_dir = common_dir + "sass"
images_dir = common_dir + "public/img"

css_dir = common_dir + "public/css"
generated_images_dir = common_dir + "public/img"
javascripts_dir = common_dir + "public/js"
#fonts_dir = "../data/compass_assets/fonts"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

add_import_path "web/bundles";
add_import_path "vendor/log0ymxm/bootswatch-scss";
