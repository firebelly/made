set :stage, :production
set :application, 'made_production'
set :domain, 'jbriseno100.webfactional.com'
set :theme, 'made'
set :login, 'jbriseno100'
set :repo_url, 'git@github.com:firebelly/made.git'
set :php, 'php70'
set :fonts_path, 'web/app/themes/made/fbmods/fonts' # This directory should be gitignored, we will need to symlink it to a corresponding directory in shared/ where we can manually add protected font files



# Simple Role Syntax
# ==================
#role :app, %w{deploy@example.com}
#role :web, %w{deploy@example.com}
#role :db,  %w{deploy@example.com}

# Extended Server Syntax
# ======================
server fetch(:domain), user: fetch(:login), roles: %w{web app db}

# you can set custom ssh options
# it's possible to pass any option but you need to keep in mind that net/ssh understand limited list of options
# you can see them in [net/ssh documentation](http://net-ssh.github.io/net-ssh/classes/Net/SSH.html#method-c-start)
# set it globally
#  set :ssh_options, {
#    keys: %w(~/.ssh/id_rsa),
#    forward_agent: false,
#    auth_methods: %w(password)
#  }

fetch(:default_env).merge!(wp_env: :production)
