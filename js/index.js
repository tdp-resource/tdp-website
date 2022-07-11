function getActivities(){
var activities = [{
	"title": "DNS解析保姆级攻略【开箱吧腾讯云】有奖互动等你来！",
	"status": "进行中",
	"statusIcon": "success",
	"link": "https://cloud.tencent.com/developer/article/2043794"
}, {
	"title": "【TDP加码福利】文档有奖反馈活动",
	"status": "进行中",
	"statusIcon": "success",
	"link": "https://cloud.tencent.com/developer/article/2042123"
}, {
	"title": "Happy Birthday ~~TDP周年庆活动来袭~~",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/2032462"
}, {
	"title": "腾云先锋第四期招募令已出，TDP男孩女孩们 速来~~",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/2014209"
}, {
	"title": "【开箱吧腾讯云——第二期对象存储COS】有奖互动来啦！",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/2007510"
}, {
	"title": "【开箱吧腾讯云——第一期lighthouse】有奖互动来啦！",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/1993405"
}, {
	"title": "#我与腾讯云不得不说的故事#有奖互动",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/1963773"
}, {
	"title": "TDP·腾讯云产品“用户实践”征文活动",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/1948608"
}, {
	"title": "TDP年末盛典活动，三大篇章共赴新春！",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "https://cloud.tencent.com/developer/article/1934619"
}, {
	"title": "双旦“协旧迎新”，组队赢取好礼！",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "/20211223/"
}, {
	"title": "腾讯云智能客服智囊团活动·第一期",
	"status": "已结束",
	"statusIcon": "secondary",
	"link": "/top.php"
}];

var temp={"<>":"li","class":"list-group-item","html":[
    {"<>":"span","class":"badge text-bg-${statusIcon}","html":"${status}"},
    {"<>":"a","href":"${link}","target":"_blank","html":" ${title}"}
  ]}


$('#activities').html(json2html.transform(activities,temp))

}