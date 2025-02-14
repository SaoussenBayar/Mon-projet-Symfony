# Utiliser l'image officielle Jenkins LTS comme base
FROM jenkins/jenkins:lts

# Passer à l'utilisateur root pour installer Docker
USER root

# Installer les paquets nécessaires pour Docker
RUN apt-get update && apt-get install -y \
    ca-certificates \
    curl \
    gnupg \
    lsb-release \
    && curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add - \
    && echo "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable" > /etc/apt/sources.list.d/docker.list \
    && apt-get update \
    && apt-get install -y docker-ce docker-ce-cli containerd.io \
    && rm -rf /var/lib/apt/lists/*  

# Donner à l'utilisateur Jenkins l'accès au socket Docker
RUN usermod -aG docker jenkins

# Passer à l'utilisateur Jenkins pour exécuter Jenkins avec moins de privilèges
USER jenkins

# Exposer le port 8080 pour Jenkins
EXPOSE 8080

# Démarrer Jenkins
ENTRYPOINT ["/bin/tini", "--", "/usr/local/bin/jenkins.sh"]
