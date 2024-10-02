<img width=150 align="right" src="https://raw.githubusercontent.com/zakandaiev/frontend-starter/main/src/public/favicon.svg" alt="HTML5 Logo">

# FrontEnd Starter

FrontEnd Starter is a boilerplate kit for easy building modern static web-sites using Gulp

## Homepage
[https://zakandaiev.github.io/frontend-starter](https://zakandaiev.github.io/frontend-starter)

## How to use

### Instalation

``` bash
# Clone the repository
git clone https://github.com/zakandaiev/frontend-starter.git

# Go to the folder
cd frontend-starter

# Install packages
npm i

# Remove the link to the original repository
# - if you use Windows system
Remove-Item .git -Recurse -Force
# - or if you use Unix system
rm -rf .git
```

### Development

``` bash
# Start development mode with live-server
npm run dev
```

### Building

``` bash
# Build static files for production
npm run build
# or
npm run prod

# Start server for build preview
npm run preview
```
