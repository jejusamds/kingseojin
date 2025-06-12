(function($) {

	/**
     * Hex to RGB
     */
	$.hexToRgb = function(hex) {
		if (hex.length == '4') {
			hex = '#' + hex.charAt(1) + hex.charAt(1) + hex.charAt(2) + hex.charAt(2) + hex.charAt(3) + hex.charAt(3);
		}
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	};

	/**
     * Component to hex
     */
	$.componentToHex = function(c) {
		var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	/**
     * RGB to hex
     */
	$.rgbToHex = function(r, g, b) {
		return "#" + $.componentToHex(r) + $.componentToHex(g) + $.componentToHex(b);
	}

	/**
     * Particles
     * @param s settings
     */
	$.fn.easyBackgroundParticles = function(s) {

		//Vars
		var settings = s;
		var ctx = this[0].getContext('2d');
		var holder = this.parent();
		var domEl = this[0];
		var particles = [];

		//Image shape
		if (settings.shape.substr(0, 5) == 'image') {
			var imageSrc = settings.shape.split(':');
			imageSrc.shift();
			imageSrc = imageSrc.join(':');
			settings.shape = 'image';
			settings.image = new Image();
			settings.image.src = imageSrc;
		}

		//Min max rand
		var minMaxRand = function(min, max) {
			return Math.random() * (max - min) + min;
		};

		//Render
		var onResize = function() {
			domEl.width = holder.innerWidth();
			domEl.height = holder.innerHeight();
		};

		//Create particle
		var createParticles = function() {
			for (var i = 0; i < settings.numParticles; i++) {
				var color = settings.colors[~~(minMaxRand(0, settings.colors.length))];
				var borderColor = settings.borderColors[~~(minMaxRand(0, settings.borderColors.length))];
				var borderOpacity = minMaxRand(settings.minBorderOpacity, settings.maxBorderOpacity);
				var opacity = minMaxRand(settings.minOpacity, settings.maxOpacity);
				particles[i] = {
					scale: minMaxRand(settings.minScale, settings.maxScale),
					x: minMaxRand(0, domEl.width),
					y: minMaxRand(0, domEl.height),
					rotation: 0,
					xpeed: minMaxRand(settings.minSpeedX, settings.maxSpeedX),
					yspeed: minMaxRand(settings.minSpeedY, settings.maxSpeedY),
					rotationSpeed: minMaxRand(settings.minRotateSpeed, settings.maxRotateSpeed),
					color: 'rgba(' + color + ',' + opacity + ')',
					opacity: opacity,
					borderColor: 'rgb(' + borderColor + ')'
				}
			}
			animateParticle();
		};

		//Draw particle
		var drawParticle = function(p) {
			ctx.fillStyle = p.color;
			if (settings.border) {
				ctx.strokeStyle = p.borderColor;
				ctx.stroke();
			}
			ctx.beginPath();
			p.rotation += p.rotationSpeed;
			ctx.save();
			ctx.translate(p.x, p.y);
			ctx.rotate(p.rotation * Math.PI / 180);
			var halfSize = (settings.baseSize * p.scale) / 2;
			switch (settings.shape) {
				case 'circle':
					ctx.arc(-halfSize, -halfSize, settings.baseSize * p.scale, 0, 2 * Math.PI, false);
					break;
				case 'square':
					ctx.fillRect(-halfSize, -halfSize, settings.baseSize * p.scale, settings.baseSize * p.scale);
					break;
				case 'image':
					ctx.globalAlpha = p.opacity;
					ctx.drawImage(settings.image, -(settings.image.width / 2), -(settings.image.height / 2), settings.image.width * p.scale, settings.image.height * p.scale);
					ctx.globalAlpha = 1.0;
					break;
			}
			ctx.restore();
			ctx.fill();
		};

		//Animate particle
		var animateParticle = function() {
			setInterval(function() {
				//clear
				ctx.clearRect(0, 0, domEl.width, domEl.height);
				//redraw
				for (var i = 0; i < settings.numParticles; i++) {
					var p = particles[i];
					p.x += p.xpeed;
					p.y += p.yspeed;
					var xsize = settings.baseSize * p.scale;
					var ysize = xsize;
					if (settings.shape == 'image') {
						xsize = settings.image.width * p.scale;
						ysize = settings.image.height * p.scale;
					}
					if (p.x > domEl.width + xsize || p.y > domEl.height + ysize || p.x < -(xsize * 1.5) || p.y < -(ysize * 1.5)) {
						resetParticle(p);
					} else {
						drawParticle(p);
					}
				}
			}, 17);
		};

		//Reset particle
		var resetParticle = function(p) {
			var random = minMaxRand(0, 1);
			var xsize = settings.baseSize * p.scale;
			var ysize = xsize;
			if (settings.shape == 'image') {
				xsize = settings.image.width * p.scale;
				ysize = settings.image.height * p.scale;
			}
			if (random > .5) {
				p.x = p.xpeed > 0 ? -xsize : domEl.width + xsize;
				p.y = minMaxRand(0, domEl.height);
			} else {
				p.x = minMaxRand(0, domEl.width);
				p.y = p.yspeed > 0 ? -ysize : domEl.height + ysize;
			}
			drawParticle(p);
		};

		//Resize
		onResize();
		$(window).on('resize.canvas', onResize);

		//Start
		createParticles();

	};

	/**
     * Easy background
     * @param s settings
     */
	$.fn.easyBackground = function(s) {

		//Settings
		var settings = {
			effect: 'particles', //particles, video, gradient, pattern, slideshow, none
			//general
			duration: 5000,
			//slideshow
			slides: [],
			slideshowEffect: 'fade', //fade, slide
			slideshowSpeed: 2000,
			//pattern
			patternImage: null,
			patternAnimationX: 'left', //left, right, none
			patternAnimationY: 'none', //top, bottom, none
			//gradient
			gradientType: 'radial', //horizontal, vertical, diagonal, radial
			gradientColors: ['#9CC4E2', '#004799'],
			gradientAnimateColors: ['#666666', '#333333'],
			gradientLoop: true,
			gradientEase: 'linear', //linear, swing
			//video
			video: 'youtube:3agk-1DohfA',
			mute: true,
			ratio: 16 / 9,
			//particles
			shape: 'circle', //circle, square, image:url
			colors: ['#FFFFFF', '255, 99, 71', '19, 19, 19'],
			border: false,
			borderColors: ['#FF0000', '#00FF00', '#0000FF'],
			minScale: .5,
			maxScale: 1,
			baseSize: 30,
			minOpacity: .1,
			maxOpacity: 1,
			minBorderOpacity: .1,
			maxBorderOpacity: 1,
			minSpeedX: -1,
			maxSpeedX: 1,
			minSpeedY: -1,
			maxSpeedY: 1,
			minRotateSpeed: .05,
			maxRotateSpeed: .1,
			numParticles: 75,
			overlay: 'dots', //dots, horizontal-lines, vertical-lines, simple-grid, grid, waves, diagonal-lines, image:url
			baseColor: '#333333',
			disableMobile: false,
			wrapNeighbours: false,
			relativeNeighbours: false
		};
		$.extend(true, settings, s);

		//Colors
		if (settings && settings.colors) {
			$(settings.colors).each(function(index, element) {
				if (element.substr(0, 1) == '#') {
					var rgb = $.hexToRgb(element);
					settings.colors[index] = rgb.r + ', ' + rgb.g + ', ' + rgb.b;
				}
			});
		}
		if (settings && settings.borderColors) {
			$(settings.borderColors).each(function(index, element) {
				if (element.substr(0, 1) == '#') {
					var rgb = $.hexToRgb(element);
					settings.borderColors[index] = rgb.r + ', ' + rgb.g + ', ' + rgb.b;
				}
			});
		}
		if (settings && settings.gradientColors) {
			$(settings.gradientColors).each(function(index, element) {
				if (element.substr(0, 1) == '#') {
					var rgb = $.hexToRgb(element);
					settings.gradientColors[index] = [rgb.r, rgb.g, rgb.b];
				}
			});
		}
		if (settings && settings.gradientAnimateColors) {
			$(settings.gradientAnimateColors).each(function(index, element) {
				if (element.substr(0, 1) == '#') {
					var rgb = $.hexToRgb(element);
					settings.gradientAnimateColors[index] = [rgb.r, rgb.g, rgb.b];
				}
			});
		}

		//Mobile check
		var noEffect = false;
		if (settings.disableMobile) {
			if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
				noEffect = true;
			}
		}

		//Background holder
		var easyBackground = $('<div class="easy-background" /> ');
		easyBackground.css('width', '100%');
		easyBackground.css('height', '100%');
		if (this.is('body')) {
			easyBackground.css('position', 'fixed');
		} else {
			easyBackground.css('position', 'absolute');
			this.css('overflow', 'hidden');
		}
		easyBackground.css('top', '0');
		easyBackground.css('left', '0');
		if (settings.baseColor) {
			easyBackground.css('background-color', settings.baseColor);
		}
		easyBackground.css('z-index', '1');
		this.append(easyBackground);

		if (settings.overlay) {
			var overlay = $('<div /> ');
			overlay.css('width', '100%');
			overlay.css('height', '100%');
			overlay.css('position', 'absolute');
			overlay.css('top', '0');
			overlay.css('left', '0');
			overlay.css('z-index', '10');
			switch (settings.overlay) {
				case 'waves':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAICAYAAADA+m62AAAAPklEQVQYV2NkwAT/gUKM6MLoAjBFGIqRFaJLovBhCrFaB7QeLg5SiEsRzJlgeQxHY/EcSOg/sQoxgwGHiQwA+f4KCL3Y/AQAAAAASUVORK5CYII=)');
					break;
				case 'horizontal-lines':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAcAAAAFCAYAAACJmvbYAAAAF0lEQVQIW2NcvHjxfwYcgBGXBEicRpIAn+0C7kufXBgAAAAASUVORK5CYII=)');
					break;
				case 'vertical-lines':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAHCAYAAADAp4fuAAAAF0lEQVQIW2NcvHjx/9jYWEYGJMA4oIIAzrccCBIzFHAAAAAASUVORK5CYII=)');
					break;
				case 'simple-grid':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAcAAAAHCAYAAADEUlfTAAAAHElEQVQIW2NcvHjxfwYcgBEkGRsby4hNftBJAgB4hhrww0B+QQAAAABJRU5ErkJggg==)');
					break;
				case 'grid':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAaklEQVQYV2NkYGAwBuKzQAwC9UA8C4ifQ/n/GaEMkCIfIG6E8iWB9DMgZoQpAOncgmTSfyBbCmQSSAFIEqYTZNIZkE6YSSAGyDi4nUC2CbKb4CphdqK7CaYAbieSb8BuAikASSKblIbsJgCKXBfTNjWx1AAAAABJRU5ErkJggg==)');
					break;
				case 'dots':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAGklEQVQIW2NkYGD4D8SMQAwGcAY2AbBKDBUAVuYCBQPd34sAAAAASUVORK5CYII=)');
					break;
				case 'diagonal-lines':
					overlay.css('background-image', 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAI0lEQVQIW2NkwAT/GdHE/gP5jMiCYAGQIpggXAAmiCIAEgQAAE4FBbECyZcAAAAASUVORK5CYII=)');
					break;
			}
			if (settings.overlay.substr(0, 5) == 'image') {
				var imageURL = settings.overlay.split(':');
				imageURL.shift();
				imageURL = imageURL.join(':');
				overlay.css('background-image', 'url(' + imageURL + ')');
			}
			easyBackground.append(overlay);
		}

		//Neighbours
		if (settings.wrapNeighbours) {
			easyBackground.siblings(':not(script)').wrap('<div class="easy-background-wrap" style="position:relative; z-index: 2;"></div>');
		} else if (settings.relativeNeighbours) {
			easyBackground.siblings(':not(script)').each(function(index, element) {
				element = $(element);
				if (element.css('position') == 'static') {
					element.css({
						'position': 'relative',
						'z-index': '2'
					});
				}
			});
		}

		//Canvas
		function setupCanvas() {
			var canvas = $('<canvas class="easy-background-canvas" />');
			canvas.css('display', 'block');
			easyBackground.append(canvas);
			return canvas;
		}

		//Video
		function youtubeBackground(video) {
			var playerHolder = $('<div id="easy-background-player" />');
			easyBackground.append(playerHolder);
			//Youtube
			if (video.substr(0, 7) == 'youtube') {
				var youtubeID = video.split(':')[1];
				if (youtubeID) {
					//Load youtube api
					var script = document.createElement('script');
					script.type = 'text/javascript';
					script.src = '//www.youtube.com/iframe_api';
					$('body').append(script);
					//on api loaded
					window.onYouTubeIframeAPIReady = function() {
						//create player
						var youtubePlayer = new YT.Player('easy-background-player', {
							width: 1,
							height: 1,
							videoId: youtubeID,
							playerVars: {
								controls: 0,
								showinfo: 0,
								modestbranding: 1,
								iv_load_policy: 3,
								wmode: 'transparent'
							},
							events: {
								'onReady': function(e) {
									youtubeResize();
									if (settings.mute) {
										e.target.mute();
									}
									e.target.seekTo(0);
									e.target.playVideo();
								},
								'onStateChange': function(e) {
									if (e.data === 0) {
										youtubePlayer.seekTo(0);
									}
								}
							}
						});
					};
					//youtube resize
					var youtubeResize = function() {
					    var win = {};
					    win.width = easyBackground.innerWidth();
					    win.height = easyBackground.innerHeight();
					    var margin = 24;
					    var overprint = 100;
					    var vid = {};
					    vid.width = win.width + ((win.width * margin) / 100);
					    vid.height = settings.ratio == "16/9" ? Math.ceil((9 * win.width) / 16) : Math.ceil((3 * win.width) / 4);
					    vid.marginTop = -((vid.height - win.height) / 2);
					    vid.marginLeft = -((win.width * (margin / 2)) / 100);
					    if (vid.height < win.height) {
						vid.height = win.height + ((win.height * margin) / 100);
						vid.width = settings.ratio == "16/9" ? Math.floor((16 * win.height) / 9) : Math.floor((4 * win.height) / 3);
						vid.marginTop = -((win.height * (margin / 2)) / 100);
						vid.marginLeft = -((vid.width - win.width) / 2);
					    }
					    vid.width += overprint;
					    vid.height += overprint;
					    vid.marginTop -= overprint / 2;
					    vid.marginLeft -= overprint / 2;
					    $('#easy-background-player').css({width: vid.width, height: vid.height, marginTop: vid.marginTop, marginLeft: vid.marginLeft});
					    
					    
					    
//						var width = easyBackground.innerWidth();
//						var height = easyBackground.innerHeight();
//						if (width / settings.ratio < height) {
//							var targWidth = Math.ceil(height * settings.ratio);
//							var targHeight = height * (targWidth / width);
//							$('#easy-background-player').width(targWidth);
//							$('#easy-background-player').height(targHeight);
//							console.log((width - targWidth) / 2);
//							console.log(height, targHeight);
//							$('#easy-background-player').css({
//								left: 0, 
//								top: (height - targHeight) / 2
//							});
//						} else {
//							var targHeight = Math.ceil(width / settings.ratio);
//							$('#easy-background-player').width(width);
//							$('#easy-background-player').height(targHeight);
//							$('#easy-background-player').css({
//								left: 0, 
//								top: (height - targHeight) / 2
//								});
//						}
//						$('#easy-background-player').css('max-width', '100%');
					};
					$(window).on('resize.youtube', youtubeResize);
				}
			}
		}

		//Gradient
		function setGradient(colorA, colorB) {
			switch (settings.gradientType) {
				case 'horizontal':
					easyBackground.css('background', '-moz-linear-gradient(left, ' + colorA + ', ' + colorB + ' 100%)');
					easyBackground.css('background', '-webkit-gradient(linear, left top, right top, color-stop(0%,' + colorA + '), color-stop(100%,' + colorB + '))');
					easyBackground.css('background', '-webkit-linear-gradient(left, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-o-linear-gradient(left, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-ms-linear-gradient(left, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', 'linear-gradient(to right, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + colorA + "', endColorstr='" + colorB + "',GradientType=1 )");
					break;
				case 'vertical':
					easyBackground.css('background', '-moz-linear-gradient(top, ' + colorA + ', ' + colorB + ' 100%)');
					easyBackground.css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,' + colorA + '), color-stop(100%,' + colorB + '))');
					easyBackground.css('background', '-webkit-linear-gradient(top, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-o-linear-gradient(top, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-ms-linear-gradient(top, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', 'linear-gradient(to bottom, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + colorA + "', endColorstr='" + colorB + "',GradientType=0 )");
					break;
				case 'diagonal':
					easyBackground.css('background', '-moz-linear-gradient(-45deg, ' + colorA + ', ' + colorB + ' 100%)');
					easyBackground.css('background', '-webkit-gradient(linear, left top, right bottom, color-stop(0%,' + colorA + '), color-stop(100%,' + colorB + '))');
					easyBackground.css('background', '-webkit-linear-gradient(-45deg, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-o-linear-gradient(-45deg, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-ms-linear-gradient(-45deg, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', 'linear-gradient(135deg, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + colorA + "', endColorstr='" + colorB + "',GradientType=1 )");
					break;
				case 'radial':
					easyBackground.css('background', '-moz-radial-gradient(center, ellipse cover, ' + colorA + ' 0%, ' + colorB + ' 100%)');
					easyBackground.css('background', '-webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,' + colorA + '), color-stop(100%,' + colorB + '))');
					easyBackground.css('background', '-webkit-radial-gradient(center, ellipse cover, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-o-radial-gradient(center, ellipse cover, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', '-ms-radial-gradient(center, ellipse cover, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('background', 'radial-gradient(ellipse at center, ' + colorA + ' 0%,' + colorB + ' 100%)');
					easyBackground.css('filter', "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + colorA + "', endColorstr='" + colorB + "',GradientType=1 )");
					break;
			}
		}

		function animateGradient(from, to) {
			var fromColors = {
				colorAR: from[0][0],
				colorAG: from[0][1],
				colorAB: from[0][2],
				colorBR: from[1][0],
				colorBG: from[1][1],
				colorBB: from[1][2]
			};
			var toColors = {
				colorAR: to[0][0],
				colorAG: to[0][1],
				colorAB: to[0][2],
				colorBR: to[1][0],
				colorBG: to[1][1],
				colorBB: to[1][2]
			};
			$(fromColors).animate(toColors, {
				duration: settings.duration,
				easing: settings.gradientEase,
				step: function() {
					var colorA = $.rgbToHex(Math.round(this.colorAR), Math.round(this.colorAG), Math.round(this.colorAB));
					var colorB = $.rgbToHex(Math.round(this.colorBR), Math.round(this.colorBG), Math.round(this.colorBB));
					setGradient(colorA, colorB);
				},
				complete: function() {
					if (settings.gradientLoop) {
						animateGradient(to, from);
					}
				}
			});
		}

		//Pattern
		function setupPattern(pattern) {
			if (!pattern) {
				if (console && console.log) {
					console.log('No pattern found.');
				}
				return false;
			}
			easyBackground.css('background-image', 'url(' + pattern + ')');
			var tmp = new Image();
			tmp.src = pattern;
			$(tmp).on('load', function() {
				animatePattern(this.width, this.height);
			});
		}
		function animatePattern(w, h) {
			easyBackground.css('background-position', '0 0');
			var startValues = {
				x: 0, 
				y: 0
			};
			var endValue = {
				x: 0, 
				y: 0
			};
			var backgroundPosition = '';
			if (settings.patternAnimationX == 'left') {
				endValue.x = -w;
			} else if (settings.patternAnimationX == 'right') {
				endValue.x = w;
			}
			if (settings.patternAnimationY == 'top') {
				endValue.y = -h;
			} else if (settings.patternAnimationY == 'bottom') {
				endValue.y = h;
			}
			$(startValues).animate(endValue, {
				duration: settings.duration,
				easing: 'linear',
				step: function() {
					var x = Math.round(this.x);
					var y = Math.round(this.y);
					easyBackground.css('background-position', x + 'px ' + y + 'px');
				},
				complete: function() {
					animatePattern(w, h);
				}
			});
		}

		//Slideshow
		function setupSlideshow(slides) {
			if (!slides || slides.length == 0) {
				if (console && console.log) {
					console.log('No slides found.');
				}
				return false;
			}
			//preload
			for (var i = 0; i < slides.length; i++) {
				var tmp = new Image();
				tmp.src = slides[i];
			}
			//holders
			var backgroundA = $('<div />');
			backgroundA.css('width', '100%');
			backgroundA.css('height', '100%');
			backgroundA.css('position', 'absolute');
			backgroundA.css('top', '0');
			backgroundA.css('left', '0');
			backgroundA.css('z-index', '2');
			backgroundA.css('-webkit-background-size', 'cover');
			backgroundA.css('-moz-background-size', 'cover');
			backgroundA.css('-o-background-size', 'cover');
			backgroundA.css('background-size', 'cover');
			backgroundA.css('background-repeat', 'no-repeat');
			backgroundA.css('background-position', 'center');
			var backgroundB = $('<div/>');
			backgroundB.css('width', '100%');
			backgroundB.css('height', '100%');
			backgroundB.css('position', 'absolute');
			backgroundB.css('top', '0');
			backgroundB.css('left', '0');
			backgroundB.css('z-index', '1');
			backgroundB.css('-webkit-background-size', 'cover');
			backgroundB.css('-moz-background-size', 'cover');
			backgroundB.css('-o-background-size', 'cover');
			backgroundB.css('background-size', 'cover');
			backgroundB.css('background-repeat', 'no-repeat');
			backgroundB.css('background-position', 'center');
			easyBackground.append(backgroundA);
			easyBackground.append(backgroundB);
			//load slide one
			setSlide(backgroundA, slides[0]);
			//slideshow
			if (slides.length > 1) {
				setTimeout(function() {
					changeSlides(backgroundA, backgroundB, 0, slides);
				}, settings.duration);
			}
		}
		function setSlide(holder, image) {
			holder.css('background-image', 'url(' + image + ')');
			holder.css('filter', "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + image + "', sizingMethod='scale')");
			holder.css('-ms-filter', "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + image + "', sizingMethod='scale')");
		}
		function changeSlides(holderA, holderB, current, slides) {
			var next = current + 1;
			if (next == slides.length) {
				next = 0;
			}
			setSlide(holderB, slides[next]);
			var effect = settings.slideshowEffect == 'slide' ? 'slideUp' : 'fadeOut';
			holderA[effect](settings.slideshowSpeed, function() {
				setSlide(holderA, slides[next]);
				holderA.show();
				setTimeout(function() {
					changeSlides(holderA, holderB, next, slides);
				}, settings.duration);
			});
		}

		//Effect
		if (!noEffect) {
			switch (settings.effect) {
				case 'particles':
					setupCanvas().easyBackgroundParticles(settings);
					break;
				case 'video':
					youtubeBackground(settings.video);
					break;
				case 'gradient':
					if (settings.gradientAnimateColors) {
						animateGradient(settings.gradientColors, settings.gradientAnimateColors);
					} else {
						var colorA = $.rgbToHex(settings.gradientColors[0][0], settings.gradientColors[0][1], settings.gradientColors[0][2]);
						var colorB = $.rgbToHex(settings.gradientColors[1][0], settings.gradientColors[1][1], settings.gradientColors[1][2]);
						setGradient(colorA, colorB);
					}
					break;
				case 'pattern':
					setupPattern(settings.patternImage);
					break;
				case 'slideshow':
					setupSlideshow(settings.slides);
					break;
			}
		}

	};

})(jQuery);