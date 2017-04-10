// Bon code !
var game = new Phaser.Game(1200, 500);
var vitesse = 300;
var positionY = 10;
var dropGravity = 100;
var i = 0;

var dropbird = {
	preload: function(){
		// Chargement image
		game.load.image('fond', 'assets/fond.png');
		game.load.image('star', 'assets/drop.png');
		game.load.image('pompe', 'assets/pompe.png');
		game.load.image('pile', 'assets/pile.png');
		game.load.spritesheet('player', 'assets/delorean.png', 93, 55, 5);
		game.load.audio('gotStar', 'assets/piece.mp3');
		game.load.audio('gotPile', 'assets/power-up.mp3');
		game.load.audio('gotPompe', 'assets/tuyau.mp3');
		game.load.audio('gameover', 'assets/game-over.mp3');
		// game.load.image('button', 'assets/bouton.png');
	},

	create: function() {
		// Setup jeu, affichage
		game.physics.startSystem(Phaser.Physics.ARCADE);		// Chargement Module Physique

		game.add.sprite(0, 0, 'fond');							// Chargement fond

		this.player = game.add.sprite(200, 350, 'player');		// Chargement asset joueur
		this.player.frame = 2;
		this.player.anchor.set(0.5);
		game.physics.arcade.enable(this.player);

		// this.button = game.add.button(game.world.centerX, game.world.centerY, 'button', actionOnClick, this)

		this.cursors = game.input.keyboard.createCursorKeys();	// Attribution réaction aux touches clavier

		this.stars = game.add.group();							// Ajout des objets à récupérer
		this.pompes = game.add.group();
		this.piles = game.add.group();

		this.gotStar = game.add.audio('gotStar');
		this.gotPile = game.add.audio('gotPile');
		this.gotPompe = game.add.audio('gotPompe');
		this.gameOver = game.add.audio('gameover');
		

		this.score = 0;
		this.labelScore = game.add.text(20, 20, 'Score: ' + this.score, {font: "30px Arial", fill: "#fff"});

		this.life = 3;
		this.labelLife = game.add.text(800, 20, 'Vies: ' + this.life, {font: "30px Arial", fill: "#fff"});

		this.vitesse = vitesse;
		this.labelVitesse = game.add.text(400, 20, 'vitesse: ' + this.vitesse, {font: "30px Arial", fill: "#fff"});

		this.timer = game.time.events.loop((Math.floor(Math.random() * 3200) + 1), this.ajoutObjet, this);
																// Drop d'objet à des moments aléatoires

		this.player.animations.add('left', [0, 1], 10, true);
		this.player.animations.add('right', [3, 4], 10, true);

	},

	update: function() {
		// Logique du jeu
		if (this.life == 0) {
			this.lostGame();
		}
		game.physics.arcade.overlap(this.player, this.stars, this.collectObjet, null, this);
		game.physics.arcade.overlap(this.player, this.pompes, this.collectPompe, null, this);
		game.physics.arcade.overlap(this.player, this.piles, this.collectPile, null, this);
		this.player.body.velocity.x = 0;
		if (this.cursors.left.isDown) {
			this.player.body.velocity.x = vitesse * -1;

			this.player.animations.play('left');

		}
		else if (this.cursors.right.isDown) {
			this.player.body.velocity.x = vitesse;

			this.player.animations.play('right');
		}
		else {
			this.player.animations.stop();
			this.player.frame = 2;
		}
	},

	lostGame : function() {
		this.gameOver.play();
		this.labelLost = game.add.text(300, game.world.centerY, 'Perdu :( Cliquez pour recommencer', {backgroundColor: "#fff", font: "30px Arial", fill: "#f00", fontWeight: "bold", align: "center"});
		vitesse = 0;
		dropGravity = 0;
		window.addEventListener("click", function(event) {
			dropGravity = 100;
			positionY = 10;
			vitesse = 300;
			this.game.state.start('dropbird');
		}, false);
	},

	restartGame : function() {
		//vitesse = vitesse + 400;
		game.state.start('dropbird');
	},

	ajoutObjet : function() {
		i = Math.floor(Math.random() * 8) + 1;
		var position = Math.floor(Math.random() * 1150) + 1;
		if (i < 6) {
			var star = game.add.sprite(position, positionY, 'star');
			game.physics.arcade.enable(star);
			star.body.gravity.y = dropGravity;

			this.stars.add(star);

			star.checkWorldBounds = true;
			star.outOfBoundsKill = true;

			star.events.onOutOfBounds.add(this.looseLife, this);
		}
		else if (i == 6) {
			var pile = game.add.sprite(position, positionY, 'pile');
			game.physics.arcade.enable(pile);
			pile.body.gravity.y = dropGravity;

			this.piles.add(pile);

			pile.checkWorldBounds = true;
			pile.outOfBoundsKill = true;
		}
		else {
			var pompe = game.add.sprite(position, positionY, 'pompe');
			game.physics.arcade.enable(pompe);
			pompe.body.gravity.y = dropGravity;

			this.pompes.add(pompe);

			pompe.checkWorldBounds = true;
			pompe.outOfBoundsKill = true;
		}
	},

	collectObjet : function(player, star) {
		star.kill();

		this.gotStar.play();

		this.score += 20;
		this.labelScore.text = 'Score: ' + this.score;
	},

	collectPompe : function(player, pompe) {
		pompe.kill();

		this.gotPompe.play();

		vitesse = vitesse - 75;
		this.vitesse -= 75;
		this.labelVitesse.text = 'Vitesse: ' + this.vitesse;
		this.score -= 10;
		this.labelScore.text = 'Score: ' + this.score;
	},

	collectPile : function(player, pile) {
		pile.kill();

		this.gotPile.play();

		vitesse = vitesse + 30;
		this.vitesse += 30;
		this.labelVitesse.text = 'Vitesse: ' + this.vitesse;
		this.score += 10;
		this.labelScore.text = 'Score: ' + this.score;
	},

	looseLife : function() {
		this.life --;
		this.labelLife.text = 'Vies: ' + this.life;
	},
	/* updateCounter: function() {

	},
	render: function() {
		game.debug.text('Temps écoulé: ' + this.game.time.totalElapsedSeconds(), 60, 20);
	} */
};

game.state.add('dropbird', dropbird);
game.state.start('dropbird');