set :application, 'made_production'
set :theme, 'made'
set :login, 'jbriseno100'
set :domain, 'made2019.firebelly.co'
set :repo_url, 'git@github.com:firebelly/made.git'
set :php, 'php74'

# This directory is not in the repo and is symlinked from shared/ on deploy, so we can add licensed font files
set :fonts_path, 'web/app/themes/made/fbmods/fonts'

# For wpcli db command search-replace
set :wpcli_remote_url, "http://#{fetch(:domain)}"
set :wpcli_local_url, "http://#{fetch(:theme)}.localhost"

# This can be overridden in a stage config file to pull from a different branch
set :branch, :master

set :deploy_to, -> { "/home/#{fetch(:login)}/webapps/#{fetch(:application)}" }
set :tmp_dir, -> { "/home/#{fetch(:login)}/tmp" }

# Use :debug for more verbose output when troubleshooting
set :log_level, :info

# Apache users with .htaccess files:
# it needs to be added to linked_files so it persists across deploys:
set :linked_files, fetch(:linked_files, []).push('.env', 'web/.htaccess', 'web/php.ini')
set :linked_dirs, fetch(:linked_dirs, []).push('web/app/uploads', fetch(:fonts_path))

namespace :deploy do
  desc 'Restart application'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      # Your restart mechanism here, for example:
      # execute :service, :nginx, :reload
    end
  end
end

# The above restart task is not run by default
# Uncomment the following line to run it on deploys if needed
# after 'deploy:publishing', 'deploy:restart'

# Set path to Composer on WebFaction
namespace :deploy do
  before :starting, :map_composer_command do
      on roles(:app) do |server|
          SSHKit.config.command_map[:composer] = "#{fetch(:php)} /home/#{fetch(:login)}/bin/composer.phar"
      end
  end
end

# GULP! compile production assets and copy to server, then UNGULP! to dev mode
# borrowing from https://gist.github.com/christhesoul/3c38053971a7b786eff2 & https://gist.github.com/nateroling/22b51c0cfbe210b00698

set :theme_path, Pathname.new('web/app/themes/').join(fetch(:theme))
set :local_app_path, Pathname.new(File.dirname(__FILE__)).join('../')
set :local_abs_path, Pathname.new(File.expand_path File.dirname(__FILE__)).join('../')
set :local_theme_path, fetch(:local_app_path).join(fetch(:theme_path))

# WebFaction-specific deploy methods, deploy:wf_setup and deploy:wf_delete
require "#{fetch(:local_abs_path)}/config/webfaction.rb"

#namespace :deploy do
#  task :compile_assets do
#    run_locally do
#      within fetch(:local_theme_path) do
#        execute :gulp, '--production'
#      end
#    end
#  end
#
#  task :ungulp do
#    run_locally do
#      within fetch(:local_theme_path) do
#        execute :gulp, '--development'
#      end
#    end
#  end
#
#  task :copy_assets do
#    invoke 'deploy:compile_assets'
#
#    on roles(:web) do
#      upload! fetch(:local_theme_path).join('dist').to_s, release_path.join(fetch(:theme_path)), recursive: true
#    end
#
#    invoke 'deploy:ungulp'
#  end
#end
#
#before "deploy:updated", "deploy:copy_assets"
