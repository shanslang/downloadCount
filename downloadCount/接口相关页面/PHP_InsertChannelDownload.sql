USE [THRecordDB]
GO

/****** Object:  StoredProcedure [dbo].[PHP_InsertChannelDownload]    Script Date: 02/13/2019 10:19:21 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




ALTER PROC [dbo].[PHP_InsertChannelDownload]
	@ChannelID INT,							
	@TypeID INT,							
	@IP	NVARCHAR(15),
	@Area NVARCHAR(32)
 AS

-- 属性设置
SET NOCOUNT ON

-- 基本信息
DECLARE @RecordID int
DECLARE @IOS_Scale int
DECLARE @IOS_OutScale int
DECLARE @Android_Scale int
DECLARE @Android_OutScale int
DECLARE @Scale int
DECLARE @OutScale int
DECLARE @today date
DECLARE @RecordID2 int
DECLARE @ipct int
BEGIN

	select @RecordID2=RecordID,@IOS_Scale=IOS_Scale,@IOS_OutScale=IOS_OutScale,@Android_Scale=Android_Scale,@Android_OutScale=Android_OutScale from THRecordDB.DBO.RecordChannelList(nolock) where ChannelID=@ChannelID and Status = 0 and Deleted = 0
	-- IF @IOS_Scale IS NULL 
	IF @RecordID2 IS NULL 
	BEGIN
		RETURN 1
	END
	
	IF @TypeID=2
	BEGIN
		SET @Scale=@IOS_Scale
		SET @OutScale=@IOS_OutScale
	END
	ELSE
	BEGIN
		SET @Scale=@Android_Scale
		SET @OutScale=@Android_OutScale
	END
	
	if @TypeID <> 1 and @TypeID <> 2 and @TypeID <> 3
		set @TypeID = 1

	set @today=GETDATE()

	INSERT INTO THRecordDB.DBO.RecordChannelDownload (ChannelID,TypeID,IP,Area) values(@ChannelID,@TypeID,@IP,@Area)
	select @ipct=count([RecordID]) FROM [THRecordDB].[dbo].[RecordChannelDownload](nolock) where [IP] = @IP
	update [THRecordDB].[dbo].[RecordChannelList] set Download_ct = Download_ct + 1 where [ChannelID] = @ChannelID and @ipct <=3
	SELECT @RecordID=RecordID from THRecordDB.DBO.RecordChannelDayCount where ChannelID=@ChannelID and InsertDate=@today and TypeID=@TypeID
	IF @RecordID IS NOT NULL
	BEGIN
		UPDATE THRecordDB.DBO.RecordChannelDayCount SET Download=Download+1 where RecordID=@RecordID and @ipct <=3
	END
	ELSE
	BEGIN
	--	INSERT INTO THRecordDB.DBO.RecordChannelDayCount (ChannelID,Download,Scale,Out_scale,InsertDate,TypeID) values(@ChannelID,1,@Scale,@OutScale,@today,@TypeID)
		if @ipct <=3
			INSERT INTO THRecordDB.DBO.RecordChannelDayCount (ChannelID,Download,Scale,Out_scale,InsertDate,TypeID) values(@ChannelID,1,@Scale,@OutScale,@today,@TypeID)
		else
			INSERT INTO THRecordDB.DBO.RecordChannelDayCount (ChannelID,Download,Scale,Out_scale,InsertDate,TypeID) values(@ChannelID,0,@Scale,@OutScale,@today,@TypeID)
	END
	
	RETURN 0
END

GO


