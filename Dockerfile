# Utiliser l'image officielle Jenkins LTS comme base 
FROM jenkins/jenkins:lts

# Passer à l'utilisateur root pour ajouter Jenkins au groupe docker
USER root

# Installer les paquets nécessaires pour Jenkins
RUN apt-get update && apt-get install -y \
    ca-certificates \
    curl \
    lsb-release \
    && rm -rf /var/lib/apt/lists/*

# Ajouter Jenkins au groupe docker (le groupe docker doit exister sur l'hôte)
RUN groupadd -g 999 docker && usermod -aG docker jenkins

# Passer à l'utilisateur Jenkins pour plus de sécurité
USER jenkins

# Exposer le port Jenkins
EXPOSE 8080

# Démarrer Jenkins
ENTRYPOINT ["/bin/tini", "--", "/usr/local/bin/jenkins.sh"]
 