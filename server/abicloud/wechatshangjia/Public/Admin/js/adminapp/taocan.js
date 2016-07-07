(function(angular) {
	'use strict';
	angular.module('taocan', ['ui.bootstrap'])
		// 时间段相关
		.controller('shijianduan', ['$rootScope', '$scope', '$uibModal', 'getTimeInfo', '$log', 'updateTimeinfo', function($rootScope, $scope, $uibModal, getTimeInfo, $log, updateTimeinfo) {
			var vm = this;
			activate();

			function activate() {
				$scope.title = '时间段信息';
				getTimeInfo.getDetail($scope.kid, InfoReady);
				// 修改时间段
				$scope.edit = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'shijianduan.html',
						controller: 'shijianduan_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': item,
									'title_action': '修改时间段',
									'shijianduantype': $scope.shijianduantype
								};
							}
						}
					});
					modalInstance.result.then(function(node) {
						// node.ciri = node.ciri === true ? '1' : '0';
						// console.log(node);
						updateTimeinfo.updateinfo(JSON.stringify(node), showmsg);
						$rootScope.root_shijianduans = $scope.shijianduans;
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};
				// 添加时间段
				$scope.add = function() {
					$scope.title_action = '添加时间段';
					var modalInstance = $uibModal.open({
						templateUrl: 'shijianduan.html',
						controller: 'shijianduan_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									items: {
										"ciri": '0',
										'ciri_starttime':'0',
										'id': '1',
										'starttime': '00:00',
										'endtime': '00:00'
									},
									'title_action': '添加时间段',
									'shijianduantype': $scope.shijianduantype
								};
							}
						}
					});
					modalInstance.result.then(function(node) {
						node.kid = $scope.kid;
						$scope.new_node = node;
						updateTimeinfo.updateinfo(JSON.stringify(node), updatetable);
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};

				function showmsg(msg) {
					console.log(msg);
				}

				function updatetable(msg) {
					console.log(msg);
					if (msg.result === '0') {
						if (msg.msg === '添加成功') {
							$scope.new_node.sid = msg.nid;
							$scope.new_node.name = $scope.shijianduantypeinfo[$scope.new_node.id];
							console.log($scope.new_node);
							$scope.shijianduans.push($scope.new_node);
							$rootScope.root_shijianduans = $scope.shijianduans;
							console.log('添加成功');
						} else {
							console.log('更新成功');
						}

					}
				}
				$scope.getshijianuanname = function(id) {
					return $scope.shijianduantypeinfo[id];
				};

				function InfoReady(content) {
					$scope.shijianduans = content.shijianduan;
					$scope.shijianduantype = content.shijianduantype;
					var shijianduantypeinfo = [];
					for (var ii in $scope.shijianduantype) {
						shijianduantypeinfo[$scope.shijianduantype[ii].id] = $scope.shijianduantype[ii].name;
					}
					$scope.shijianduantypeinfo = shijianduantypeinfo;
					$rootScope.root_shijianduantypeinfo = $scope.shijianduantypeinfo;
					$rootScope.root_shijianduans = $scope.shijianduans;
				}

			}
		}])
		.controller('shijianduan_ModalInstanceCtrl', function($scope, $uibModalInstance, items) {
			console.log(items.items);
			// 初始化窗口变量
			$scope.title_action = items.title_action;
			$scope.times = ['00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30'];
			$scope.shijianduantypes = items.shijianduantype;
			items.items.ciri = items.items.ciri === '1' ? true : false;
			items.items.ciri_starttime = items.items.ciri_starttime === '1' ? true : false;
			items.items.status = items.items.status === '1' ? true : false;
			items.items.shouye = items.items.shouye === '1' ? true : false;
			// $scope.taocaninfo.sun = $scope.taocaninfo.sun === '1' ? true : false;
			$scope.node = items.items;


			$scope.ok = function() {
				// $scope.taocaninfo.sun = $scope.taocaninfo.sun === false ? '0' : '1';
				$scope.node.ciri = $scope.node.ciri === false ? '0' : '1';
				$scope.node.ciri_starttime = $scope.node.ciri_starttime === false ? '0' : '1';
				$scope.node.status = $scope.node.status === false ? '0' : '1';
				$scope.node.shouye = $scope.node.shouye === false ? '0' : '1';
				$uibModalInstance.close($scope.node);
			};

			$scope.cancel = function() {
				$scope.node.ciri = $scope.node.ciri === false ? '0' : '1';
				$scope.node.ciri_starttime = $scope.node.ciri_starttime === false ? '0' : '1';
				$scope.node.status = $scope.node.status === false ? '0' : '1';
				$scope.node.shouye = $scope.node.shouye === false ? '0' : '1';
				$uibModalInstance.dismiss('cancel');
			};
		})
		// 房型相关
		.controller('roomtype', ['$rootScope', '$scope', 'getRoomInfo', '$uibModal', 'updateRoominfo', '$log', function($rootScope, $scope, getRoomInfo, $uibModal, updateRoominfo, $log) {
			activate();

			function activate() {
				$scope.title = '房型信息';
				getRoomInfo.getDetail($scope.kid, InfoReady);
				$scope.getshouyeinfo = function(info) {
					if (info === '0') {
						return '否';
					} else {
						return '是';
					}
				};

				$scope.edit = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'roomtype.html',
						controller: 'roomtype_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': item,
									'title_action': '修改房型信息'
								};
							}
						}
					});
					modalInstance.result.then(function(roominfo) {

						updateRoominfo.updateinfo(JSON.stringify(roominfo), updatetable);
						$rootScope.root_roominfos = $scope.roominfos;
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};
				$scope.add = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'roomtype.html',
						controller: 'roomtype_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': {
										'count': '',
										'des': '',
										'name': '',
										'shouye': '0',
										'status': '1',
										'ktvid': $scope.kid
									},
									'title_action': '添加房型信息'
								};
							}
						}
					});
					modalInstance.result.then(function(roominfo) {
						console.log(roominfo);
						$scope.roominfo_new = roominfo;
						updateRoominfo.updateinfo(JSON.stringify(roominfo), updatetable);
						$rootScope.root_roominfos = $scope.roominfos;
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};

				function updatetable(msg) {
					console.log(msg);
					if (msg.result === '0') {
						if (msg.msg === '添加成功') {
							$scope.roominfo_new.id = msg.tid;
							$scope.roominfos.push($scope.roominfo_new);
							console.log($scope.roominfo_new);
							console.log('添加成功');
						} else {
							console.log('更新成功');

						}
					}
				}


				function InfoReady(content) {
					$scope.roominfos = content.sort(function(b, a) {
						return (a.shouye > b.shouye) ? 1 : ((b.shouye > a.shouye) ? -1 : 0);
					});
					$rootScope.root_roominfos = $scope.roominfos;
				}
			}

		}])
		.controller('roomtype_ModalInstanceCtrl', ['$scope', '$uibModalInstance', 'items', function($scope, $uibModalInstance, items) {
			$scope.roominfo = items.items;
			var renshu = $scope.roominfo.des.split('-');
			if (renshu.length == 2) {
				$scope.roominfo.des_s = renshu[0];
				$scope.roominfo.des_e = renshu[1];
			}
			$scope.roominfo.shouye = $scope.roominfo.shouye === '0' ? false : true;
			$scope.roominfo.status = $scope.roominfo.status === '0' ? false : true;
			$scope.title_action = items.title_action;
			$scope.ok = function() {
				$scope.roominfo.shouye = $scope.roominfo.shouye === false ? '0' : '1';
				$scope.roominfo.status = $scope.roominfo.status === false ? '0' : '1';
				$scope.roominfo.des = $scope.roominfo.des_s + '-' + $scope.roominfo.des_e;
				$uibModalInstance.close($scope.roominfo);
			};

			$scope.cancel = function() {
				$uibModalInstance.dismiss('cancel');
			};
		}])
		.controller('tiaokuan', ['$scope', 'deltiaokuan_s', 'gettiaokuanInfo', '$uibModal', 'updateTiaokuaninfo', '$log', function($scope, deltiaokuan_s, gettiaokuanInfo, $uibModal, updateTiaokuaninfo, $log) {
			var vm = this;
			activate();

			function activate() {
				$scope.title = '条款信息';
				gettiaokuanInfo.getDetail($scope.kid, InfoReady);
				$scope.edit = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'tiaokuan.html',
						controller: 'tiaokuan_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': item,
									'title_action': '修改条款信息'
								};
							}
						}
					});
					modalInstance.result.then(function(tiaokuaninfo) {

						updateTiaokuaninfo.updateinfo(JSON.stringify(tiaokuaninfo), updatetable);
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};
				$scope.add = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'tiaokuan.html',
						controller: 'tiaokuan_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': {
										'name': '',
										'ktvid': $scope.kid
									},
									'title_action': '添加条款信息'
								};
							}
						}
					});
					modalInstance.result.then(function(tiaokuaninfo) {
						console.log(tiaokuaninfo);
						$scope.tiaokuaninfo_new = tiaokuaninfo;
						updateTiaokuaninfo.updateinfo(JSON.stringify(tiaokuaninfo), updatetable);
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};

				$scope.del = function(item) {
					console.log(item.id);
					alert('请确认要删除"' + item.name + '"吗？');
					deltiaokuan_s.del(item.id, del_succ);


					function del_succ(msg) {
						console.log(msg);
						$scope.tiaokuans.splice($.inArray(item, $scope.tiaokuans), 1);
					}

				};

				function updatetable(msg) {
					console.log(msg);
					if (msg.result === '0') {
						if (msg.msg === '添加成功') {
							$scope.tiaokuaninfo_new.id = msg.tid;
							$scope.tiaokuans.push($scope.tiaokuaninfo_new);
							console.log($scope.tiaokuaninfo_new);
							console.log('添加成功');
						} else {
							console.log('更新成功');

						}
					}
				}

				function InfoReady(content) {
					$scope.tiaokuans = content;
				}
			}

		}])
		.controller('tiaokuan_ModalInstanceCtrl', ['$scope', '$uibModalInstance', 'items', function($scope, $uibModalInstance, items) {
			$scope.tiaokuaninfo = items.items;
			$scope.title_action = items.title_action;
			$scope.ok = function() {
				$uibModalInstance.close($scope.tiaokuaninfo);
			};

			$scope.cancel = function() {
				$uibModalInstance.dismiss('cancel');
			};
		}])
		.controller('taocan-content', ['$rootScope', '$scope', 'deltaocan', 'getTaocanInfo', '$uibModal', '$log', 'updatetaocaninfo', function($rootScope, $scope, deltaocan, getTaocanInfo, $uibModal, $log, updatetaocaninfo) {
			var vm = this;
			activate();

			function activate() {
				$scope.title = '套餐信息';
				getTaocanInfo.getDetail($scope.kid, InfoReady);
				$scope.getshouyeinfo = function(info) {
					if (info === '0') {
						return '否';
					} else {
						return '是';
					}
				};
				$scope.edit = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'taocan.html',
						controller: 'taocan_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': item,
									'title_action': '修改套餐信息'
								};
							}
						}
					});
					modalInstance.result.then(function(roominfo) {

						updatetaocaninfo.updateinfo(JSON.stringify(roominfo), updatetable);
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};
				$scope.add = function(item) {
					var modalInstance = $uibModal.open({
						templateUrl: 'taocan.html',
						controller: 'taocan_ModalInstanceCtrl',
						resolve: {
							items: function() {
								return {
									'items': {

										'ktvid': $scope.kid

									},
									'title_action': '添加套餐信息'
								};
							}
						}
					});
					modalInstance.result.then(function(taocaninfo) {
						console.log(taocaninfo);
						$scope.roominfo_new = taocaninfo;
						updatetaocaninfo.updateinfo(JSON.stringify(taocaninfo), updatetable);
					}, function() {
						$log.info('Modal dismissed at: ' + new Date());
					});
				};
				$scope.del = function(item) {
					console.log(item.id);
					alert('请确认要删除"' + item.name + '"吗？');
					deltaocan.del(item.id, del_succ);

					function del_succ(msg) {
						console.log(msg);
						$scope.taocans.splice($.inArray(item, $scope.taocans), 1);
					}
				};
				$scope.getshijianuanname = function(id) {
					return $scope.shijianduannameinfo[id];
				};
				$scope.getroomtypename = function(id) {
					return $scope.roomnameinfo[id];
				};

				function updatetable(msg) {
					console.log(msg);
					if (msg.result === '0') {
						if (msg.msg === '添加成功') {
							$scope.roominfo_new.id = msg.tid;
							$scope.taocans.push($scope.roominfo_new);
							console.log('添加成功');
						} else {
							console.log('更新成功');
						}

					}
				}

				function InfoReady(content) {
					$scope.taocans = getDays(content);
					var roomnameinfo = [];
					for (var iii in $rootScope.root_roominfos) {
						roomnameinfo[$rootScope.root_roominfos[iii].id] = $rootScope.root_roominfos[iii].name;
					}
					$scope.roomnameinfo = roomnameinfo;
					var shijianduannameinfo = [];
					for (var ii in $rootScope.root_shijianduans) {
						shijianduannameinfo[$rootScope.root_shijianduans[ii].sid] = $rootScope.root_shijianduans[ii].name;
					}
					$scope.shijianduannameinfo = shijianduannameinfo;

					function getDays(content) {
						for (var node in content) {
							var days = [];
							days.push({
								'name': '日',
								'status': content[node].sun
							});
							days.push({
								'name': '一',
								'status': content[node].mon
							});

							days.push({
								'name': '二',
								'status': content[node].tue
							});

							days.push({
								'name': '三',
								'status': content[node].wen
							});

							days.push({
								'name': '四',
								'status': content[node].thu
							});

							days.push({
								'name': '五',
								'status': content[node].fri
							});

							days.push({
								'name': '六',
								'status': content[node].sat
							});
							content[node].days = days;
						}
						return content.sort(function(b, a) {
							return (a.shouye > b.shouye) ? 1 : ((b.shouye > a.shouye) ? -1 : 0);
						});
					}
				}
			}
		}])
		.controller('taocan_ModalInstanceCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'items', function($rootScope, $scope, $uibModalInstance, items) {
			// console.log($rootScope.root_shijianduans);
			// console.log($rootScope.root_shijianduantypeinfo);
			// console.log($rootScope.root_roominfos);
			$scope.shijianduan_scope = $rootScope.root_shijianduans;
			$scope.roominfos_scope = $rootScope.root_roominfos;
			for (var iii in $scope.shijianduan_scope) {
				$scope.shijianduan_scope[iii].ciritxt = $scope.shijianduan_scope[iii].ciri === '0' ? '' : '次日';
			}
			$scope.taocaninfo = items.items;
			$scope.taocaninfo.shouye = $scope.taocaninfo.shouye === '1' ? true : false;
			$scope.taocaninfo.is_yd_price = $scope.taocaninfo.is_yd_price === '1' ? true : false;
			$scope.taocaninfo.mon = $scope.taocaninfo.mon === '1' ? true : false;
			$scope.taocaninfo.tue = $scope.taocaninfo.tue === '1' ? true : false;
			$scope.taocaninfo.wen = $scope.taocaninfo.wen === '1' ? true : false;
			$scope.taocaninfo.thu = $scope.taocaninfo.thu === '1' ? true : false;
			$scope.taocaninfo.fri = $scope.taocaninfo.fri === '1' ? true : false;
			$scope.taocaninfo.sat = $scope.taocaninfo.sat === '1' ? true : false;
			$scope.taocaninfo.sun = $scope.taocaninfo.sun === '1' ? true : false;
			$scope.title_action = items.title_action;
			$scope.taocaninfo.shichang = parseInt($scope.taocaninfo.shichang);
			$scope.taocaninfo.shichangs = [];
			for (var ii = 0; ii < $scope.shijianduan_scope.length; ii++) {
				var st = '2016-05-15T' + $scope.shijianduan_scope[ii].starttime;
				var et = '2016-05-15T' + $scope.shijianduan_scope[ii].endtime;
				var stime = new Date(st);
				var etime = new Date(et);
				var longtimes = etime - stime > 0 ? ((etime - stime) / 3600000) : 24 + (etime - stime) / 3600000;
				$scope.taocaninfo.shichangs[$scope.shijianduan_scope[ii].sid] = [];
				$scope.taocaninfo.shichangs[$scope.shijianduan_scope[ii].sid].push({
					"name": "欢唱到结束",
					"value": 0
				});

				for (var i1 = 1; i1 <= longtimes; i1++) {
					$scope.taocaninfo.shichangs[$scope.shijianduan_scope[ii].sid].push({
						"name": "欢唱" + i1 + "小时",
						"value": i1
					});
				}
			}
			// console.log($scope.taocaninfo.shichangs);
			$scope.shichang_change = $scope.taocaninfo.shichangs[$scope.taocaninfo.shijianduanid];
			$scope.change_shijainduan = function() {
				$scope.shichang_change = $scope.taocaninfo.shichangs[$scope.taocaninfo.shijianduanid];
			};

			$scope.selweekend = function() {
				$scope.taocaninfo.fri = $scope.selweekends;
				$scope.taocaninfo.sat = $scope.selweekends;
			};
			$scope.selworkday = function() {
				$scope.taocaninfo.mon = $scope.selworkdays;
				$scope.taocaninfo.tue = $scope.selworkdays;
				$scope.taocaninfo.wen = $scope.selworkdays;
				$scope.taocaninfo.thu = $scope.selworkdays;
				$scope.taocaninfo.sun = $scope.selworkdays;
			};
			$scope.ok = function() {
				$scope.taocaninfo.shouye = $scope.taocaninfo.shouye === false ? '0' : '1';
				$scope.taocaninfo.is_yd_price = $scope.taocaninfo.is_yd_price === false ? '0' : '1';
				$scope.taocaninfo.mon = $scope.taocaninfo.mon === false ? '0' : '1';
				$scope.taocaninfo.tue = $scope.taocaninfo.tue === false ? '0' : '1';
				$scope.taocaninfo.wen = $scope.taocaninfo.wen === false ? '0' : '1';
				$scope.taocaninfo.thu = $scope.taocaninfo.thu === false ? '0' : '1';
				$scope.taocaninfo.fri = $scope.taocaninfo.fri === false ? '0' : '1';
				$scope.taocaninfo.sat = $scope.taocaninfo.sat === false ? '0' : '1';
				$scope.taocaninfo.sun = $scope.taocaninfo.sun === false ? '0' : '1';
				console.log('提交的信息为');
				console.log($scope.taocaninfo);
				$uibModalInstance.close($scope.taocaninfo);
			};

			$scope.cancel = function() {
				$scope.taocaninfo.shouye = $scope.taocaninfo.shouye === false ? '0' : '1';
				$scope.taocaninfo.is_yd_price = $scope.taocaninfo.is_yd_price === false ? '0' : '1';
				$scope.taocaninfo.mon = $scope.taocaninfo.mon === false ? '0' : '1';
				$scope.taocaninfo.tue = $scope.taocaninfo.tue === false ? '0' : '1';
				$scope.taocaninfo.wen = $scope.taocaninfo.wen === false ? '0' : '1';
				$scope.taocaninfo.thu = $scope.taocaninfo.thu === false ? '0' : '1';
				$scope.taocaninfo.fri = $scope.taocaninfo.fri === false ? '0' : '1';
				$scope.taocaninfo.sat = $scope.taocaninfo.sat === false ? '0' : '1';
				$scope.taocaninfo.sun = $scope.taocaninfo.sun === false ? '0' : '1';
				$uibModalInstance.dismiss('cancel');
			};
		}])
		.controller('zonglan', ['$scope', 'getZonglan', function($scope, getZonglan) {
			var vm = this;
			activate();

			function activate() {
				$scope.title = '套餐总览';
				getZonglan.getDetail($scope.kid, InfoReady);

				//function showtips() {
				//	$('.taocan-tips').tooltipster({
				//		content: $('<img src="doc/images/spiderman.png" width="50" height="50" /><p style="text-align:left;"><strong>Soufflé chocolate cake powder.</strong> Applicake lollipop oat cake gingerbread.</p>'),
				//		// setting a same value to minWidth and maxWidth will result in a fixed width
				//		minWidth: 300,
				//		maxWidth: 300,
				//		position: 'right'
				//	});
				//	console.log('tips ok');
				//}
				//showtips();

				function InfoReady(content) {
					$scope.zonglan = getdetail(content);

					function getdetail(content) {
						var shijianduans = {};
						for (var i in content) {
							var taocan_detail = content[i].name;
							if (shijianduans.hasOwnProperty(content[i].shijianduanid) === false) {
								shijianduans[content[i].shijianduanid] = {};
								shijianduans[content[i].shijianduanid].mon = [];
								shijianduans[content[i].shijianduanid].tue = [];
								shijianduans[content[i].shijianduanid].wen = [];
								shijianduans[content[i].shijianduanid].thu = [];
								shijianduans[content[i].shijianduanid].fri = [];
								shijianduans[content[i].shijianduanid].sat = [];
								shijianduans[content[i].shijianduanid].sun = [];
								shijianduans[content[i].shijianduanid].shijianduan = content[i].shijianduan;
							}
							if (content[i].mon === '1') {
								shijianduans[content[i].shijianduanid].mon.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
							if (content[i].tue === '1') {
								shijianduans[content[i].shijianduanid].tue.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
							if (content[i].wen === '1') {
								shijianduans[content[i].shijianduanid].wen.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
							if (content[i].thu === '1') {
								shijianduans[content[i].shijianduanid].thu.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
							if (content[i].fri === '1') {
								shijianduans[content[i].shijianduanid].fri.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
							if (content[i].sat === '1') {
								shijianduans[content[i].shijianduanid].sat.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
							if (content[i].sun === '1') {
								shijianduans[content[i].shijianduanid].sun.push({
									'name': content[i].name,
									'content': content[i]
								});
							}
						}
						console.log(shijianduans);
						return shijianduans;
					}
				}
			}
		}])
		.service('getZonglan', ['$http', function($http) {
			this.getDetail = getDetail;

			function getDetail(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/getZonglan/kid/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}])
		.service('getTimeInfo', ['$http', function($http) {
			this.getDetail = getDetail;

			function getDetail(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/getTimeInfo/kid/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}])
		.service('getRoomInfo', ['$http', function($http) {
			this.getDetail = getDetail;

			function getDetail(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/getRoomInfo/kid/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}])
		.service('getTaocanInfo', ['$http', function($http) {
			this.getDetail = getDetail;

			function getDetail(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/getTaocanInfo/kid/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}])
		.service('deltaocan', ['$http', function($http) {
			this.del = del;

			function del(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/deltaocan/id/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}])
		.service('deltiaokuan_s', ['$http', function($http) {
			this.del = del;

			function del(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/deltiaokuan_s/id/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}])
		.service('updateTimeinfo', ['$http', function($http) {
			this.updateinfo = updateinfo;

			function updateinfo(info, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/updateTimeinfo',
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache
				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http({
						'method': 'POST',
						'url': DetailURL,
						'data': info
					})
					.success(onReady)
					.error(onError);
			}
		}])
		.service('updateRoominfo', ['$http', function($http) {
			this.updateinfo = updateinfo;

			function updateinfo(info, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/updateRoominfo',
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache
				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http({
						'method': 'POST',
						'url': DetailURL,
						'data': info
					})
					.success(onReady)
					.error(onError);
			}
		}])
		.service('updatetaocaninfo', ['$http', function($http) {
			this.updateinfo = updateinfo;

			function updateinfo(info, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/updatetaocaninfo',
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache
				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http({
						'method': 'POST',
						'url': DetailURL,
						'data': info
					})
					.success(onReady)
					.error(onError);
			}
		}])
		.service('updateTiaokuaninfo', ['$http', function($http) {
			this.updateinfo = updateinfo;

			function updateinfo(info, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/updateTiaokuaninfo',
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache
				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http({
						'method': 'POST',
						'url': DetailURL,
						'data': info
					})
					.success(onReady)
					.error(onError);
			}
		}])
		.service('gettiaokuanInfo', ['$http', function($http) {
			this.getDetail = getDetail;

			function getDetail(id, onReady, onError) {
				var DetailJson = '/wechatshangjia/Admin/Xktv/gettiaokuanInfo/kid/' + id,
					DetailURL = DetailJson + '?v=' + (new Date().getTime()); // jumps cache

				onError = onError || function() {
					alert('Failure loading Detail Content');
				};

				$http
					.get(DetailURL)
					.success(onReady)
					.error(onError);
			}
		}]);
})(window.angular);