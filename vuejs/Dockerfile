# base image
FROM node:10.15.0

# set working directory
RUN mkdir /www
WORKDIR /www

COPY package*.json ./

# install and cache app dependencies
RUN npm install
RUN npm install -g @vue/cli

# add `/www/node_modules/.bin` to $PATH
ENV PATH /www/node_modules/.bin:$PATH

# start app
CMD ["npm", "run", "serve"]
